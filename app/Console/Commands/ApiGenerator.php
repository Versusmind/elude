<?php namespace App\Console\Commands;

/******************************************************************************
 *
 * @package     Myo 2
 * @copyright   © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link        http://www.versusmind.eu/
 *
 * @file        ApiGenerator.php
 * @author      LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description ApiGenerator
 *
 ******************************************************************************/

use App\Libraries\Generator\Generator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Process\Process;

class ApiGenerator extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:generate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate api resources';

    public static $defaultComposerPath = '/usr/local/bin/composer';

    public static $databaseType = [
        'string',
        'text',
        'mediumText',
        'longText',

        'tinyInteger',
        'smallInteger',
        'integer',
        'mediumInteger',
        'bigInteger',
        'float',

        'boolean',
        'char',
        'dateTime',
        'date',

        'timestamp'
    ];

    public static $databaseToApiType = [
        'string' => 'String',
        'integer' => 'Number',
        'bigInteger' => 'Number',
        'boolean' => 'Boolean',
        'char' => 'String',
        'dateTime' => 'Datetime',
        'date' => 'Date',
        'float' => 'Number',
        'mediumText' => 'String',
        'mediumInteger' => 'Number',
        'smallInteger' => 'Number',
        'text' => 'String',
        'tinyInteger' => 'Number',
        'timestamp' => 'Timestamp',
        'longText' => 'String',
    ];

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $name = $this->argument('name');

        $isUserRestrict = $this->confirm('User restricted ?', false);

        $author = $this->ask("Your username", Cache::get('developer.username', ''));

        Cache::forever('developer.username', $author);

        $generator = new Generator($name, $isUserRestrict, $author);
        $templateData = $generator->getTemplateData();
        $files = $generator->getFiles();

        $foreignKeys = $this->getForeignKeys();
        if ($isUserRestrict) {
            $foreignKeys[] = 'User';
        }
        $foreignKeys = array_unique($foreignKeys);

        $fields = $this->getFields();
        $this->summary($templateData, $isUserRestrict);
        $this->fieldsSummary($fields);
        $this->generate($generator, $fields, $foreignKeys);
        $this->runComposerDumpAutoload();
        $this->migrateDatabase();
        $this->generateDocumentation();

        $this->info("");
        $this->info("What you need to do now ?");
        $this->info("\t [] Add Acl/Scope to your route in routes.php");
        $this->info("\t [] Fill data provider for " . $files['controllerTest']);
        $this->info("\t [] Fill data provider for " . $files['repositoryTest']);
    }

    public function summary($templateData, $isUserRestrict)
    {
        $this->info('Data:');
        $this->info("\t Model name:" . $templateData['modelName']);
        $this->info("\t Table name:" . $templateData['tableName']);
        $this->info("\t Date:" . $templateData['date']);
        $this->info("\t Author:" . $templateData['author']);
        $this->info("\t User restricted ?" . ($isUserRestrict ? 'Yes' : 'No'));
    }

    public function fieldsSummary(array $fields)
    {
        $this->info("\t Fields:");

        foreach ($fields as $field) {
            $this->fieldSummary($field);
        }
    }

    public function foreignSummary(array $foreignKeys)
    {
        $this->info("\t Foreign keys on models:");

        foreach ($foreignKeys as $foreignKey) {
            $this->info("\t\t" . $foreignKey);
        }
    }

    public function fieldSummary($field)
    {
        $this->info("\t\t" . $field['name'] . ':');
        $this->info("\t\t\t Database type:" . $field['type']);
        $this->info("\t\t\t Fillable:" . $field['fillable']);
        $this->info("\t\t\t Required:" . $field['required']);
        $this->info("\t\t\t Nullable:" . $field['nullable']);
        $this->info("\t\t\t Unsigned:" . $field['unsigned']);
        $this->info("\t\t\t Rules:" . $field['rules']);
        $this->info("\t\t\t Api type:" . $field['apiType']);
        $this->info("\t\t\t Default:" . (empty($field['default']) ? 'No' : $field['default']));
    }


    public function generate(Generator $generator, $fields, $foreignKeys)
    {
        if ($this->confirm('Generate migration/model/repository/controller ?', true)) {
            $generator->generate($fields, $foreignKeys);
            $this->info("Generated files:");
            $files = $generator->getFiles();
            foreach ($files as $file) {
                $this->info("\t " . $file);
            }
            $this->info("Updated files:");
            $this->info("\t app" . DIRECTORY_SEPARATOR . "Http" . DIRECTORY_SEPARATOR . "routes.php");

        } else {
            if ($this->confirm('Generate migration ?', true)) {
                $generator->migration($fields, $foreignKeys);
                $this->comment('Migration generated');
            }

            if ($this->confirm('Generate model ?', true)) {
                $generator->model($fields, $foreignKeys);
                $this->comment('Model generated');
            }

            if ($this->confirm('Generate repository + tests ?', true)) {
                $generator->repository($fields, $foreignKeys);
                $this->comment('Repository generated');
            }

            if ($this->confirm('Generate controller + tests ?', true)) {
                $generator->controller($fields, $foreignKeys);
                $this->comment('Controller generated');
            }

            if ($this->confirm('Update routes.php file ?', true)) {
                $generator->route($foreignKeys);
                $this->comment('Route added');
            }
        }
    }


    public function migrateDatabase()
    {
        if ($this->confirm('Migrate database ?', true)) {
            \Artisan::call('migrate');
            $this->info('Database migrated');
        }
    }

    public function generateDocumentation()
    {
        if ($this->confirm('Update documentations ?', true)) {
            \Artisan::call('apidoc:generate');
            $this->info('Api doc generated');
            \Artisan::call('phpdoc:generate');
            $this->info('Php doc generated');
        }
    }


    public function runComposerDumpAutoload()
    {
        $this->info('');
        $this->comment("Run composer dump-autoload");
        $path = self::$defaultComposerPath;
        $composerInstalled = is_file($path);
        if (!$composerInstalled) {
            $path = base_path('composer.phar');
            $composerInstalled = is_file($path);
        }

        if (!$composerInstalled) {
            $this->error("No composer installation found, please run composer dump-autoload");

            return;
        }

        $process = new Process('php ' . $path . ' dump-autoload');
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Composer error: ' . $process->getOutput());
        }
    }

    /**
     * @author LAHAXE Arnaud
     *
     *
     * @return array
     */
    public function getFields()
    {
        $fields = [];
        $this->info('For validation please refer to http://laravel.com/docs/5.1/validation#available-validation-rules');
        while ($this->confirm('Add a new fields ?', true)) {
            $field = [
                'name' => $this->ask('Field name', null),
                'type' => $this->choice('Type ', self::$databaseType, 0),
                'fillable' => $this->confirm('Fillable ?', true),
                'required' => $this->confirm('Required ?', true),
                'nullable' => $this->confirm('Nullable ?', false),
            ];

            if ($this->confirm('Add a validator (except required) ?', false)) {
                $field['rules'] = $this->ask('Specific validators (except required)', '');
            } else {
                $field['rules'] = '';
            }

            if ($this->confirm('Set a default value ?', false)) {
                $field['default'] = $this->ask('Default ?', '');
            } else {
                $field['default'] = '';
            }

            $field['apiType'] = self::$databaseToApiType[$field['type']];

            if (self::$databaseToApiType[$field['type']] === 'Number') {
                $field['unsigned'] = $this->confirm('Unsigned ?', false);
            } else {
                $field['unsigned'] = false;
            }

            $fields[] = $field;
        }

        return $fields;
    }

    public function getForeignKeys()
    {
        if (!$this->confirm('Does the model have foreign keys ?', false)) {
            return [];
        }

        $foreignKeysTable = [];
        $availableModels = [];

        $finder = new Finder();
        $files = $finder->depth(0)->files()->in(base_path('app'));

        /** @var File $file */
        foreach ($files as $file) {
            $availableModels[] = $file->getBasename('.php');
        }

        $addOther = true;
        while ($addOther) {
            $foreignKeysTable[] = strtolower($this->choice("What model ?", $availableModels));

            $addOther = $this->confirm('Add new foreign key ?', true);
        }

        return $foreignKeysTable;
    }
}
