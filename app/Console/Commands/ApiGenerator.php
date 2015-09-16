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
        'integer',
        'bigInteger',
        'boolean',
        'char',
        'dateTime',
        'date',
        'float',
        'mediumText',
        'mediumInteger',
        'smallInteger',
        'text',
        'tinyInteger',
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
        'timestamp' => 'Timestamp'
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

        $fields = $this->getFields();
        $this->summary($templateData, $isUserRestrict, $fields);
        $this->generate($generator, $fields);
        $this->runComposerDumpAutoload();
        $this->migrateDatabase();
        $this->generateDocumentation();

        $this->info("");
        $this->info("What you need to do now ?");
        $this->info("\t [] Add Acl/Scope to your route in routes.php");
        $this->info("\t [] Fill data provider for " . $files['controllerTest']);
        $this->info("\t [] Fill data provider for " . $files['repositoryTest']);
    }

    public function summary($templateData, $isUserRestrict, $fields)
    {
        $this->info('Data:');
        $this->info("\t Model name:" . $templateData['modelName']);
        $this->info("\t Table name:" . $templateData['tableName']);
        $this->info("\t Date:" . $templateData['date']);
        $this->info("\t Author:" . $templateData['author']);
        $this->info("\t User restricted ?" . ($isUserRestrict ? 'Yes' : 'No'));
        $this->info("\t Fields:");
        foreach ($fields as $field) {
            $this->info("\t\t" . $field['name'] . ':');
            $this->info("\t\t\t Database type:" . $field['type']);
            $this->info("\t\t\t Fillable:" . ($field['fillable'] ? 'Yes' : 'No'));
            $this->info("\t\t\t Required:" . ($field['required'] ? 'Yes' : 'No'));
            $this->info("\t\t\t Nullable:" . ($field['nullable'] ? 'Yes' : 'No'));
            $this->info("\t\t\t Rules:" . $field['rules']);
            $this->info("\t\t\t Api type:" . $field['apiType']);
        }
    }

    public function generate(Generator $generator, $fields)
    {
        if ($this->confirm('Generate migration/model/repository/controller ?', true)) {
            $generator->generate($fields);
            $this->info("Generated files:");
            $files = $generator->getFiles();
            foreach ($files as $file) {
                $this->info("\t " . $file);
            }
            $this->info("Updated files:");
            $this->info("\t app" . DIRECTORY_SEPARATOR . "Http" . DIRECTORY_SEPARATOR . "routes.php");

        } else {
            if ($this->confirm('Generate migration ?', true)) {
                $generator->migration($fields);
                $this->comment('Migration generated');
            }

            if ($this->confirm('Generate model ?', true)) {
                $generator->model($fields);
                $this->comment('Model generated');
            }

            if ($this->confirm('Generate repository + tests ?', true)) {
                $generator->repository($fields);
                $this->comment('Repository generated');
            }

            if ($this->confirm('Generate controller + tests ?', true)) {
                $generator->controller($fields);
                $this->comment('Controller generated');
            }

            if ($this->confirm('Update routes.php file ?', true)) {
                $generator->route();
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
        while ($this->confirm('Add a new fields ?', true) || empty($fields)) {
            $field = [
                'name' => $this->ask('Field name: ', null),
                'type' => $this->choice('Type name: ', self::$databaseType, 0),
                'fillable' => $this->confirm('Fillable ?', true),
                'required' => $this->confirm('Required ?', true),
                'nullable' => $this->confirm('Nullable ?', false),
                'rules' => $this->ask('Specific validators (except required):', '')
            ];

            $field['apiType'] = self::$databaseToApiType[$field['type']];

            $fields[] = $field;
        }

        return $fields;
    }
}
