<?php namespace App\Libraries\Assets\Tasks\Javascript;

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
 * @file Babel.php
 * @author LAHAXE Arnaud
 * @last-edited 06/05/16
 * @description Copy
 *
 ******************************************************************************/



use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use Illuminate\Support\Facades\Config;
use League\Pipeline\StageInterface;
use Symfony\Component\Process\Process;

/**
 * User: LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 06/05/2016
 * Time: 12:13
 * FileName : Babel.php
 * Project : myo2
 */
class Babel implements StageInterface
{

    /**
     * Process the payload.
     *
     * @param Collection $collection
     *
     * @return mixed
     */
    public function process($collection)
    {

        if (!in_array($collection->getGroupName(), Config::get('assets.babelCollectionsEnabled'))) {
            return $collection;
        }

        \Log::debug('Assets::Babel on collection ' . $collection->getCollectionId());
        $newAssets = [];
        $toCompiled = [];
        /** @var Asset $asset */
        foreach ($collection->getType(Asset::JS) as $asset) {

            // change the file extension to compiled.js
            $outputFile = $collection->getTmpDirectory() . DIRECTORY_SEPARATOR . str_replace([
                    base_path('resources/assets/'),
                    DIRECTORY_SEPARATOR,
                    '.js'
                ],[
                    '',
                    '-',
                    ''
                ], $asset->getPath()) . '.compiled.js';


            // compile only if the timestamp of the original file has changed
            if (!file_exists($outputFile) || filemtime($outputFile) < filemtime($asset->getInitialPath())) {

                // we build an array of files to transpile
                $toCompiled[$asset->getPath()] = $outputFile;
            }

            $newAssets[] = new Asset(Asset::JS, $outputFile, $asset->getInitialPath());
        }
        $collection->setType(Asset::JS, $newAssets);


        // we create a babel tmp workplace to transpile
        $outputBabelTempDirectory = $collection->getTmpDirectory() . DIRECTORY_SEPARATOR . 'babel';
        if (!mkdir($outputBabelTempDirectory)) {
            \Log::error("Assets::Babel fail to create " . $outputBabelTempDirectory);
            return $collection;
        }

        if (count($toCompiled) > 0) {
            // transpile with es2015 presets to the tmp folder
            $process = new Process(sprintf('babel --presets es2015 %s --out-dir %s', implode(' ', array_keys($toCompiled)), $outputBabelTempDirectory));
            $process->run();
            if (!$process->isSuccessful()) {
                \Log::error("Assets::Babel " . $process->getErrorOutput());
            } else {
                // we move transpiled files to they destinations
                foreach ($toCompiled as $originalPath => $destinationPath) {
                    $transpileFilePath = $outputBabelTempDirectory . DIRECTORY_SEPARATOR . $originalPath;
                    if (file_exists($transpileFilePath)) {
                        rename($transpileFilePath, $destinationPath);
                    } else {
                        \Log::warn("Assets::Babel " . $transpileFilePath . " tranpiled file not found");
                    }
                }
            }
        }


        return $collection;
    }
}