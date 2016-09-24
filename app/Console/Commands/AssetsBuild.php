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
 * @file AssetsBuild.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description AssetsBuild
 *
 ******************************************************************************/

use App\Models\Service;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class AssetsBuild extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:build {--watch=0 : Watch files changes and live reload} {--prod=0 : Prod build with uglify, concat and version freeze}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build assets';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info("Build assets");
        $cmd = sprintf('node ./node_modules/gassetic/bin.js %s --env=%s', $this->option('watch')? '': 'build', $this->option('prod') ? 'prod':'dev');
        $this->info($cmd);

        $process = new Process($cmd);
        $process->setTimeout($this->option('watch')? 86400: 30);

        $process->run(function ($type, $buffer) {
            // prevent double line break
            $buffer = str_replace("\n", "", $buffer);

            if ('err' === $type) {
                $this->error($buffer);
            } else {
                $this->info($buffer);
            }
        });

        if (!$process->isSuccessful()) {
            $this->error($process->getErrorOutput());
        }

        $this->comment(\PHP_Timer::resourceUsage());
    }
}
