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
 * @file ApiDocGenerator.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description ApiDocGenerator
 *
 ******************************************************************************/

use App\Models\Service;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ApiDocGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apidoc:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Api doc';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (\App::environment() === 'production') {
            $this->error('This feature is not available on this server');
        }

        $process = new Process('apidoc -i ' . base_path('app/Http/Controllers') . ' -o ' . base_path('../doc'));

        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Impossible to generate doc');
            $this->error($process->getErrorOutput());
            $this->info('You need to install apidoc: (sudo) npm install apidoc -g');

            return;
        }

        $this->comment('Documentation generated in folder ' . base_path('../doc'));
        $this->comment(\PHP_Timer::resourceUsage());
    }
}
