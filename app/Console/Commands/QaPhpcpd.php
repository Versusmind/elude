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

class QaPhpcpd extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qa:phpcpd {--limit=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'PHP copy past detector';

    /**
     * @throws \Exception
     */
    public function handle()
    {
        \Log::info('QA::PHPCPD Run copy past detector');

        $detector = new \SebastianBergmann\PHPCPD\Detector\Detector(new \SebastianBergmann\PHPCPD\Detector\Strategy\DefaultStrategy);
        $finder = new FinderFacade(
            ['app'],
            [] // exclude
        );
        $files = $finder->findFiles();
        $clones = $detector->copyPasteDetection($files, 10);

        if($this->getOutput()->getVerbosity() > 1) {
            $printer = new Text();
            $printer->printResult($this->getOutput(), $clones);
            unset($printer);
        }

        $percentage = floatval($clones->getPercentage());
        if($percentage > $this->option('limit')) {
            $this->error('[Shame] The copy/paste percentage is ' . $percentage);

            throw new \Exception('Your code is bad, and you should feel bad');
        }
    }
}