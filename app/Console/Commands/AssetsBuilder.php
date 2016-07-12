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



use App\Libraries\Assets\Orchestrator;
use Illuminate\Console\Command;
use duncan3dc\Helpers\Fork;

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

        if ($groupToBuild === 'all') {
            $this->info('Clean assets');
            \Artisan::call('assets:clean');
        }

        if (config('assets.concat')) {
            $this->info('Build with concatenation');
        } else {
            $this->info('Build without concatenation');
        }

        $this->info("");

        \Log::info('Assets::Build all groups');


        /** @var Orchestrator $ocherstator */
        $ocherstator = \App::make('App\Libraries\Assets\Orchestrator');

        $fork = new Fork ();

        foreach (array_keys(config('assets.groups')) as $groupname) {
            if ($groupToBuild !== 'all' && $groupToBuild !== $groupname) {
                continue;
            }


            $fork->call(function () use ($groupname, $ocherstator) {
                $collection = \App\Libraries\Assets\Collection::createByGroup($groupname);

                $message = "\t <info>- Build " . $groupname . '</info>' . PHP_EOL;

                try {
                    $buildTypes = $ocherstator->build($collection);

                    if (count($buildTypes) === 0) {
                        $message .= "\t\t <comment> No build </comment>" . PHP_EOL;
                    } else {
                        foreach ($buildTypes as $buildType) {
                            $message .= "\t\t <comment>- " . $buildType . " build</comment>" . PHP_EOL;
                        }
                    }

                    $this->line($message);

                } catch (\Exception $e) {
                    $this->error($e);

                    return;
                }
            });
        }

        $fork->wait();

        $this->info("");
        $this->info('Build successful');

        $this->comment(\PHP_Timer::resourceUsage());
    }
}
