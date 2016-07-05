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
        \Log::debug('Assets::Babel on collection ' . $collection->getCollectionId());

        $newAssets = [];
        /** @var Asset $asset */
        foreach ($collection->getType(Asset::JS) as $asset) {
            if (preg_match('/(.*)\.min\.js/', $asset->getPath())) {
                $newAssets[] = new Asset(Asset::JS, $asset->getPath(), $asset->getInitialPath());
                continue;
            }

            // change the file extension to compiled.js
            $outputFile = str_replace(['.js'], [''], $asset->getPath()) . '.compiled.js';


            // compile only if the timestamp of the original file has changed
            if (!file_exists($outputFile) || filemtime($outputFile) < filemtime($asset->getInitialPath())) {

                $process = new Process(sprintf('babel %s --out-file %s', $asset->getPath(), $outputFile));
                $process->run();
                if(!$process->isSuccessful()) {
                    \Log::error("Assets::Babel " . $process->getErrorOutput());
                }
            }

            $newAssets[] = new Asset(Asset::JS, $outputFile, $asset->getInitialPath());
        }
        $collection->setType(Asset::JS, $newAssets);

        return $collection;
    }
}