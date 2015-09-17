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
use Clockwork\Support\Lumen\Facade as Clockwork;


/**
 * Class Orchestrator
 *
 *
 *
 * @package App\Libraries\Assets
 *
 * @author  LAHAXE Arnaud
 */
class Orchestrator
{
    public static $buildType = [
        Asset::JS       => 'javascript',
        Asset::CSS      => 'style',
        Asset::LESS     => 'style',
        Asset::SASS     => 'style',
        Asset::FONT     => 'font',
        Asset::IMG      => 'image',
        Asset::TEMPLATE => 'template',
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
    protected function initialize(Collection $assets)
    {
        $assets->setTmpDirectory(config('assets.tmpDirectory') . DIRECTORY_SEPARATOR . $assets->getCollectionId());
        $assets->initializeFolder();
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     *
     * @return mixed
     */
    public function font(Collection $assets)
    {
        Clockwork::startEvent('assets.font', 'Assets build fonts.');
        $result = $this->pipeline->font()->process($assets);
        Clockwork::endEvent('assets.font');

        return $result;
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     *
     * @return mixed
     */
    public function template(Collection $assets)
    {

        Clockwork::startEvent('assets.template', 'Assets build template.');

        $result =  $this->pipeline->template()->process($assets);

        Clockwork::endEvent('assets.template');

        return $result;
    }

    /**
     * @param Collection $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     *
     * @return mixed
     */
    public function image(Collection $assets)
    {

        Clockwork::startEvent('assets.image', 'Assets build images.');

        $result =   $this->pipeline->image()->process($assets);

        Clockwork::endEvent('assets.image');

        return $result;
    }

    /**
     * @param Collection|string $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     *
     * @return mixed
     */
    public function javascript($assets)
    {
        if(is_string($assets)) {
            $assets = $this->getCollectionByName($assets);
        }

        Clockwork::startEvent('assets.javascript', 'Assets build javascripts.');

        $buildNeeded = $this->buildDetector->isBuildNeeded($assets);
        if ($buildNeeded) {
            $this->initialize($assets);
        }

        $result =   $this->pipeline->javascript(true, $buildNeeded)->process($assets);

        Clockwork::endEvent('assets.javascript');

        return $result;
    }

    /**
     * @param Collection|string $assets
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     *
     * @return mixed
     */
    public function style($assets)
    {
        if(is_string($assets)) {
            $assets = $this->getCollectionByName($assets);
        }

        Clockwork::startEvent('assets.style', 'Assets build styles.');

        $buildNeeded = $this->buildDetector->isBuildNeeded($assets);
        if ($buildNeeded) {
            $this->initialize($assets);
        }

        $result =   $this->pipeline->style(true, $buildNeeded)->process($assets);

        Clockwork::endEvent('assets.style');

        return $result;
    }

    /**
     * @param Collection $collection
     * @param $buildType
     *
     * @return mixed
     */
    public function buildType(Collection $collection, $buildType)
    {
        return $this->{$buildType}($collection);
    }

    /**
     * @param Collection $collection
     * @param array $except
     *
     * @return array
     */
    public function build(Collection $collection, array $except = array())
    {
        $buildNeeded = $this->buildDetector->getBuildNeeded($collection, $except);

        if (count($buildNeeded) === 0) {

            return [];
        }

        foreach ($buildNeeded as $buildType) {
            $this->buildType($collection, $buildType);
        }

        return $buildNeeded;
    }

    protected function getCollectionByName($name)
    {
        return Collection::createByGroup($name);
    }
}
