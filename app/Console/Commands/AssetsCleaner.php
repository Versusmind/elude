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
 * @file AssetsCleaner.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description AssetsCleaner
 *
 ******************************************************************************/


use App\Models\Service;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class AssetsCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove build assets';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info('Assets::Clean cleaning build assets');

        $baseAssetsPath = base_path('public/assets/');
        if (!is_dir($baseAssetsPath)) {
            $this->warn(sprintf("Folder %s does not exists", $baseAssetsPath));

            return;
        }

        if ($handle = opendir($baseAssetsPath)) {
            while (false !== ($entry = readdir($handle))) {
                $path = $baseAssetsPath . '/' . $entry;
                if ($entry != "." && $entry != ".." && is_dir($path)) {
                    $this->info('Delete public/assets/' . $entry);
                    $this->deleteDir($path);
                }
            }
            closedir($handle);
        }


        $this->comment(\PHP_Timer::resourceUsage());
    }

    public function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            $this->error($dirPath . ' is not a folder');

            return;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}
