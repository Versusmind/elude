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
use SensioLabs\Security\Formatters\TextFormatter;
use SensioLabs\Security\SecurityChecker;
use Symfony\Component\Process\Process;

class QaPhpComposerSecurity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qa:composer-security';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check vulnerabities on composer dependencies';

    /**
     * @throws \Exception
     */
    public function handle()
    {
        \Log::info('QA::COMPOSER-SECURITY Run composer security checker');


        $checker = new SecurityChecker();
        $alerts  = $checker->check('composer.lock');

        if ($alerts !== false && !empty($alerts)) {
            $formatter = new TextFormatter($this->getHelperSet()->get('formatter'));
            $formatter->displayResults($this->output, 'composer.lock', $alerts);

            throw new \Exception('Vulnerability detected');
        }


        $this->info("No vulnerability detected");
    }
}
