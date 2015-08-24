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
 * @file QaPhpmd.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description QaPhpmd
 *
 ******************************************************************************/

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class QaPhpmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qa:phpmd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PHP mess detector';

    /**
     * @throws \Exception
     */
    public function handle()
    {
        \Log::info('QA::PHPMD Run mess detector');

        $process = new Process('.' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'phpmd app xml phpmd.xml');
        if ($this->getOutput()->getVerbosity() > 1) {
            $this->comment('Run ' . $process->getCommandLine());
        }

        $process->run();

        $outputXml   = $process->getOutput();
        $this->info($outputXml);

        $violations  = [];
        $dom         = simplexml_load_string($outputXml);
        $nbViolation = 0;

        foreach ($dom->xpath('//pmd/file') as $file) {
            foreach ($file->children() as $violation) {
                $violations[(string)$violation['ruleset']][(string)$violation['rule']][] = [
                    'file'    => str_replace(base_path(), '', (string)$file['name']),
                    'line'    => ((string)$violation["beginline"]) . ' to ' . ((string)$violation['endline']),
                    'message' => trim((string) $violation),
                ];
                $nbViolation ++;
            }
        }

        if ($nbViolation == 0) {
            $this->comment('No violation detected ! kudos !');

            return;
        }


        if ($this->getOutput()->getVerbosity() > 1) {
            foreach ($violations as $name => $ruleset) {
                $this->info($name);
                foreach ($ruleset as $name => $ruleViolations) {
                    $this->info("\t" . $name);
                    $this->table(['file', 'lines', 'message'], $ruleViolations);
                }
            }
        }

        $this->error($nbViolation . ' violations detected. Please fix your code');

        if ($this->getOutput()->getVerbosity() == 1) {
            $this->getOutput()->writeln('');
            $this->info('Add -v to your commant to show details');
        }

        throw new \Exception('Your code is bad, and you should feel bad');
    }
}
