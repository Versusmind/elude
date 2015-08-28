<?php namespace App\Libraries\Assets\Tasks;

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
 * Date: 07/08/2015
 * Time: 12:13
 * FileName : Concat.php
 * Project : myo2
 */
class Copy implements StageInterface
{
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @param Collection $collection
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     *
     * @return Collection|mixed
     */
    public function process($collection)
    {
        \Log::info('Assets::Copy on collection ' . $collection->getCollectionId());

        $outputDirectory = $collection->getOutputDirectory() . $this->type . DIRECTORY_SEPARATOR;
        if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0777, true)) {
            throw new \RuntimeException('Fail to create ' . $outputDirectory);
        }

        $newAssetsFiles = [];
        foreach ($collection->getType($this->type) as $asset) {
            $relativePath = $this->getRelativeBuildFilePath($asset, $collection);
            $this->createSubFolders($relativePath, $outputDirectory);

            copy($asset->getPath(), $outputDirectory . $relativePath);
            $asset->setPath($outputDirectory . $relativePath);

            $newAssetsFiles[] = $asset;
        }

        $collection->setType($this->type, $newAssetsFiles);

        return $collection;
    }

    /**
     * @param Asset $asset
     * @param Collection $collection
     *
     * @return mixed
     */
    protected function getRelativeBuildFilePath(Asset $asset, Collection $collection)
    {
        // file is in tmp folder
        if (strpos($asset->getPath(), $collection->getTmpDirectory()) !== false) {
            return str_replace($collection->getTmpDirectory(), '', $asset->getPath());
            // file is in bower folder
        } elseif (strpos($asset->getPath(), $collection->getBowerDirectory()) !== false) {
            $relativePath = str_replace($collection->getBowerDirectory(), '', $asset->getPath());
            if ($asset->getType() === Asset::FONT) {
                $relativePath = last(explode('/', $relativePath));
            }

            return $relativePath;
        }

        return str_replace(config('assets.assetsDirectory') . DIRECTORY_SEPARATOR . $this->type . DIRECTORY_SEPARATOR, '', $asset->getPath());
    }

    /**
     * @param $relativePath
     * @param $directory
     */
    protected function createSubFolders($relativePath, $directory)
    {
        if (strpos($relativePath, '/') !== false) {
            $subfolders = implode('/', explode(DIRECTORY_SEPARATOR, $relativePath, -1));

            if (!is_dir($directory . $subfolders)) {
                if (!mkdir($directory . $subfolders, 0777, true)) {
                    throw new \RuntimeException('Cannot create ' . $directory . $subfolders . ' directory');
                }
            }
        }
    }
}
