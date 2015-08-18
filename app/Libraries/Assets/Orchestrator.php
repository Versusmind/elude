<?php namespace App\Libraries\Assets;

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
 * @file Orchestrator.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Orchestrator
 *
 ******************************************************************************/


use App\Libraries\Assets\Pipeline\Development;
use App\Libraries\Assets\Pipeline\Pipeline;
use App\Libraries\Assets\Pipeline\Production;

/**
 * Class Orchestrator
 *
 *
 *
 * @package App\Libraries\Assets
 * @author  LAHAXE Arnaud
 */
class Orchestrator
{
    public static $buildType = [
        Asset::JS => 'javascript',
        Asset::CSS => 'style',
        Asset::LESS => 'style',
        Asset::SASS => 'style',
        Asset::FONT => 'font',
        Asset::IMG => 'image',
        Asset::TEMPLATE => 'template'
    ];

    /**
     * @var Pipeline
     */
    protected $pipeline;

    /**
     * Orchestrator constructor.
     */
    public function __construct()
    {
        if (!config('assets.concat')) {
            $this->pipeline = new Development();
        } else {
            $this->pipeline = new Production();
        }
    }


    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     */
    protected function initialize (Collection $assets)
    {
        $assets->setTmpDirectory(config('assets.tmpDirectory') . DIRECTORY_SEPARATOR . $assets->getCollectionId());
        $assets->initializeFolder();
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function font (Collection $assets)
    {
        \Log::info('Assets::Build start font build for collection ' . $assets->getCollectionId());

        return $this->pipeline->font()->process($assets);
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function template (Collection $assets)
    {
        \Log::info('Assets::Build start template build for collection ' . $assets->getCollectionId());

        return $this->pipeline->template()->process($assets);
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function image (Collection $assets)
    {
        \Log::info('Assets::Build start image build for collection ' . $assets->getCollectionId());

        return $this->pipeline->image()->process($assets);
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function javascript (Collection $assets)
    {
        $buildNeeded = $this->isBuildNeeded($assets);
        if($buildNeeded) {
            $this->initialize($assets);
            \Log::info('Assets::Build start javascript build for collection ' . $assets->getCollectionId());
        }

        return $this->pipeline->javascript(true, $buildNeeded)->process($assets);
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function style (Collection $assets)
    {
        $buildNeeded = $this->isBuildNeeded($assets);
        if($buildNeeded) {
            $this->initialize($assets);
            \Log::info('Assets::Build start style build for collection ' . $assets->getCollectionId());
        }

        return $this->pipeline->style(true, $buildNeeded)->process($assets);
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return bool
     */
    public function isBuildNeeded (Collection $assets)
    {
        if (!is_file($assets->versionFilePath())) {
            return TRUE;
        }

        // get version detail
        $build = json_decode(file_get_contents($assets->versionFilePath()));
        if ($build === FALSE) {
            return TRUE;
        }

        // change concat option
        if ($build->concat !==  config('assets.concat')) {
            return TRUE;
        }

        // no need to re-test in production, files must not change
        if (\App::environment() !== 'production') {

            // check if files change since last version
            foreach ($assets->getAssets() as $types) {
                foreach ($types as $file) {
                    if (filemtime($file->getPath()) > $build->time) {
                        return TRUE;
                    }
                }
            }
        }

        if ($this->reloadFromBuild($assets, $build)) {

            return FALSE;
        }

        return TRUE;
    }

    /**
     * @param Collection $assets
     * @param $build
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return bool
     */
    protected function reloadFromBuild (Collection $assets, $build)
    {
        \Log::info('Assets::Reload reload build for collection ' . $assets->getCollectionId());

        foreach ($build->build as $buildFile) {
            if(!file_exists($buildFile->path)) {
                return false;
            }
        }

        $assets->setAssets([]);
        foreach ($build->build as $buildFile) {
            $assets->appendType($buildFile->type, new Asset($buildFile->type, $buildFile->path));
        }

        return true;
    }

    /**
     * @param Collection $collection
     * @param array $except
     * @return array
     */
    public function getBuildNeeded(Collection $collection, array $except = array())
    {
        $buildNeeded = [];
        foreach(Collection::$types as $type) {
            if($collection->hasType($type) && !in_array($type, $except, true)) {
                $buildNeeded[] = Orchestrator::$buildType[$type];
            }
        }

        return array_unique($buildNeeded);
    }

    /**
     * @param Collection $collection
     * @param $buildType
     * @return mixed
     */
    public function buildType(Collection $collection, $buildType)
    {
        return $this->{$buildType}($collection);
    }

    /**
     * @param Collection $collection
     * @param array $except
     * @return array
     */
    public function build(Collection $collection, array $except = array())
    {
        $buildNeeded = $this->getBuildNeeded($collection, $except);

        if(count($buildNeeded) === 0) {
            return [];
        }

        foreach($buildNeeded as $buildType) {
            $this->buildType($collection, $buildType);
        }

        return $buildNeeded;
    }
}