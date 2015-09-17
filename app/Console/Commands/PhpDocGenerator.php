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
 * @file PhpDocGenerator.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description PhpDocGenerator
 *
 ******************************************************************************/

use App\Models\Service;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class PhpDocGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpdoc:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Php doc';

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

        $command = base_path('vendor' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'apigen') . ' generate -s app  -d  phpdoc';

        $this->comment($command);

        $process = new Process($command);

        $process->run(function ($type, $buffer) {
            $buffer = trim($buffer);
            if(empty($buffer)) {
                return;
            }

            if ('err' === $type) {
                $this->error($buffer);
            } else {
                $this->comment($buffer);
            }
        });

        if (!$process->isSuccessful()) {
            $this->error($process->getErrorOutput());
            return;
        }

        $this->comment('Documentation generated in folder ' . base_path('phpdoc'));
        $this->comment(\PHP_Timer::resourceUsage());
    }
}
