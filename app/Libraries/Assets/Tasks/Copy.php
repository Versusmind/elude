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
        \Log::debug('Assets::Copy on collection ' . $collection->getCollectionId());

        if($this->type !== Asset::TEMPLATE) {
        $outputDirectory = $collection->getOutputDirectory() . $this->type . DIRECTORY_SEPARATOR;
        } else {
            $outputDirectory = $collection->getOutputDirectory() . Asset::JS . DIRECTORY_SEPARATOR;
        }
        if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0777, TRUE)) {
            throw new \RuntimeException('Fail to create ' . $outputDirectory);
        }

        $newAssetsFiles = [];
        foreach ($collection->getType($this->type) as $asset) {

            $relativePath = $this->getOuputRelativePath($asset, $collection);

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

    /**
     * @param Asset $asset
     * @param Collection $collection
     *
     * @return mixed
     */
    protected function getOuputRelativePath(Asset $asset, Collection $collection)
    {
        // file is in tmp folder
        if (!empty($collection->getTmpDirectory()) && strpos($asset->getPath(), $collection->getTmpDirectory()) !== FALSE) {
            $relativePath = str_replace($collection->getTmpDirectory(), '', $asset->getPath());
            // file is in bower folder
        } elseif (!empty($collection->getBowerDirectory()) && strpos($asset->getPath(), $collection->getBowerDirectory()) !== FALSE) {
            $relativePath = str_replace($collection->getBowerDirectory(), '', $asset->getPath());
            if ($asset->getType() === Asset::FONT) {

                $relativePath = last(explode('/', $relativePath));
            }

            // templates files are not separated from js files
        } elseif($this->type === Asset::TEMPLATE) {
            $relativePath = str_replace(config('assets.assetsDirectory') . DIRECTORY_SEPARATOR . Asset::JS . DIRECTORY_SEPARATOR, '', $asset->getPath());

            // file is resource/assets folder (no other case)
        } else {
            $relativePath = str_replace(config('assets.assetsDirectory') . DIRECTORY_SEPARATOR . $this->type . DIRECTORY_SEPARATOR, '', $asset->getPath());
        }

        return $relativePath;
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
        if (!empty($collection->getTmpDirectory()) && strpos($asset->getPath(), $collection->getTmpDirectory()) !== false) {
            return str_replace($collection->getTmpDirectory(), '', $asset->getPath());
            // file is in bower folder
        } elseif (strpos($asset->getPath(), $collection->getBowerDirectory()) !== false) {

            $relativePath = str_replace($collection->getBowerDirectory(), '', $asset->getPath());
            if ($asset->getType() === Asset::FONT) {
                $relativePath = last(explode(DIRECTORY_SEPARATOR, $relativePath));
            }

            return $relativePath;
        }

        return str_replace(str_replace('/', DIRECTORY_SEPARATOR, config('assets.assetsDirectory')) . DIRECTORY_SEPARATOR . $this->type . DIRECTORY_SEPARATOR, '', $asset->getPath());
    }

    /**
     * @param $relativePath
     * @param $directory
     */
    protected function createSubFolders($relativePath, $directory)
    {
        if (strpos($relativePath, DIRECTORY_SEPARATOR) !== false) {
            $subfolders = implode(DIRECTORY_SEPARATOR, explode(DIRECTORY_SEPARATOR, $relativePath, -1));

            if (!is_dir($directory . $subfolders)) {
                if (!mkdir($directory . $subfolders, 0777, true)) {
                    throw new \RuntimeException('Cannot create ' . $directory . $subfolders . ' directory');
                }
            }
        }
    }
}
