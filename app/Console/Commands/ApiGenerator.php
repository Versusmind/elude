<?php namespace App\Console\Commands;

/******************************************************************************
 *
 * @package Myo 2
 * @copyright © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link http://www.versusmind.eu/
 *
 * @file ApiGenerator.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description ApiGenerator
 *
 ******************************************************************************/

use App\Libraries\Generator\Generator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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

        $this->info('Data:');
        $this->info("\t Model name:" . $templateData['modelName']);
        $this->info("\t Table name:" . $templateData['tableName']);
        $this->info("\t Date:"       . $templateData['date']);
        $this->info("\t Author:"     . $templateData['author']);

        if($this->confirm('Generate migration/model/repository/controller ?', true)) {
            $generator->generate();
            $this->info("Generated files:");
            foreach($files as $file) {
                $this->info("\t " . $file);
            }
            $this->info("Updated files:");
            $this->info("\t app". DIRECTORY_SEPARATOR . "Http". DIRECTORY_SEPARATOR . "routes.php");

        } else {
            if($this->confirm('Generate migration ?', true)) {
                $generator->migration();
                $this->comment('Migration generated');
            }

            if($this->confirm('Generate model ?', true)) {
                $generator->model();
                $this->comment('Model generated');
            }

            if($this->confirm('Generate repository + tests ?', true)) {
                $generator->repository();
                $this->comment('Repository generated');
            }

            if($this->confirm('Generate controller + tests ?', true)) {
                $generator->controller();
                $this->comment('Controller generated');
            }

            if($this->confirm('Update routes.php file ?', true)) {
                $generator->route();
                $this->comment('Route added');
            }
        }

        $this->info("");
        $this->info("What you need to do now ?");
        $this->info("\t [] Edit " . $files['migration'] . " to add your fields");
        $this->info("\t [] Edit " . $files['model'] . " to fill the fillable attribute");
        $this->info("\t [] Edit " . $files['model'] . " to fill the rules attribute");
        $this->info("\t [] Add Acl/Scope to your route in routes.php");
        $this->info("\t [] Fill data provider for " . $files['controllerTest']);
        $this->info("\t [] Fill data provider for " . $files['repositoryTest']);
        $this->info("\t [] php artisan migrate");
    }
}
