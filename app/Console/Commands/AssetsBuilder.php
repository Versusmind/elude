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
 * @file AssetsBuilder.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description AssetsBuilder
 *
 ******************************************************************************/


use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use App\Libraries\Assets\Orchestrator;
use App\Models\Service;
use Illuminate\Console\Command;

class AssetsBuilder extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:build {--group=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build assets groups';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $groupToBuild = $this->option('group');

        if($groupToBuild === 'all') {
            $this->info('Clean assets');
            \Artisan::call('assets:clean');
        }

        if(config('assets.concat')) {
            $this->info('Build with concatenation');
        } else {
            $this->info('Build without concatenation');
        }

        $this->info("");

        \Log::info('Assets::Build all groups');


        /** @var Orchestrator $ocherstator */
        $ocherstator = \App::make('App\Libraries\Assets\Orchestrator');

        foreach(config('assets.groups') as $groupname => $assets)
        {
            if($groupToBuild !== 'all' && $groupToBuild !== $groupname) {
                continue;
            }

            $collection = \App\Libraries\Assets\Collection::createByGroup($groupname);

            $this->info("\t - Build " . $groupname);

            try {
                $buildTypes = $ocherstator->build($collection);

                if(count($buildTypes) === 0) {
                    $this->comment('No build');
                } else {
                    foreach ($buildTypes as $buildType) {
                        $this->comment("\t\t - " . $buildType . " build");
                    }
                }
            } catch (\Exception $e) {
                $this->error($e);
                return;
            }
        }

        $this->info("");
        $this->info('Build successful');

        $this->comment(\PHP_Timer::resourceUsage());
    }
}
