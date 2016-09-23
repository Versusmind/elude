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
 * @file Copy.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Copy
 *
 ******************************************************************************/


use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;

/**
 * User: LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 07/08/2015
 * Time: 12:13
 * FileName : Concat.php
 * Project : myo2
 */
class Copy implements StageInterface
{

    protected $type;

    function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @param Collection $collection
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return Collection|mixed
     */
    public function process($collection)
    {
        \Log::debug('Assets::Copy on collection ' . $collection->getCollectionId());


        $outputDirectory = $collection->getOutputDirectory() . Asset::getOutputFolder($this->type) . DIRECTORY_SEPARATOR;

        if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0777, TRUE)) {
            throw new \RuntimeException('Fail to create ' . $outputDirectory);
        }

        $newAssetsFiles = [];
        foreach ($collection->getType($this->type) as $asset) {
            if (strpos($asset->getPath(), $collection->getBowerDirectory()) !== FALSE) {
                $relativePath = str_replace($collection->getBowerDirectory(), '', $asset->getPath());
                if ($asset->getType() === Asset::FONT) {

                    $relativePath = last(explode('/', $relativePath));
                }

            } else {
                $relativePath = str_replace(config('assets.assetsDirectory') . DIRECTORY_SEPARATOR . $this->type . DIRECTORY_SEPARATOR, '', $asset->getPath());
            }

            if (strpos($relativePath, '/') !== FALSE) {
                $subfolders = join('/', explode(DIRECTORY_SEPARATOR, $relativePath, -1));

                if (!is_dir($outputDirectory . $subfolders)) {
                    if (!mkdir($outputDirectory . $subfolders, 0777, TRUE)) {
                        throw new \RuntimeException('Cannot create ' . $outputDirectory . $subfolders . ' directory');
                    }
                }
            }

            copy($asset->getPath(), $outputDirectory . $relativePath);
            $asset->setPath($outputDirectory . $relativePath);

            $newAssetsFiles[] = $asset;
        }

        $collection->setType($this->type, $newAssetsFiles);

        return $collection;
    }
}