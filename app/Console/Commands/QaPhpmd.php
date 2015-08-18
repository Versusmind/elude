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
 * @file QaPhpcpd.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description QaPhpcpd
 *
 ******************************************************************************/

use Illuminate\Console\Command;
use SebastianBergmann\FinderFacade\FinderFacade;
use SebastianBergmann\PHPCPD\Log\Text;
use Symfony\Component\Process\Process;

class QaPhpmd extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qa:phpmd {--limit=5}';

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

        $process = new Process('bower update');

        $process->run();

        $outputXml = $process->getOutput();

        // parse XML

        
    }
}