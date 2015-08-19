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
     * @var BuilderDetector
     */
    protected $buildDetector;

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

        $this->buildDetector = new BuilderDetector();
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
        $buildNeeded = $this->buildDetector->isBuildNeeded($assets);
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
        $buildNeeded = $this->buildDetector->isBuildNeeded($assets);
        if($buildNeeded) {
            $this->initialize($assets);
            \Log::info('Assets::Build start style build for collection ' . $assets->getCollectionId());
        }

        return $this->pipeline->style(true, $buildNeeded)->process($assets);
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
        $buildNeeded = $this->buildDetector->getBuildNeeded($collection, $except);

        if(count($buildNeeded) === 0) {
            return [];
        }

        foreach($buildNeeded as $buildType) {
            $this->buildType($collection, $buildType);
        }

        return $buildNeeded;
    }
}