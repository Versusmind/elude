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

use App\Libraries\Generator\Generator;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

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

        $author = $this->ask("Your username", '');

        $generator = new Generator($name, $isUserRestrict, $author);

        $generator->generate();
        // info
    }
}
