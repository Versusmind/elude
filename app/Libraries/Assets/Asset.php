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
 * @file Asset.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Asset
 *
 ******************************************************************************/


class Asset implements \JsonSerializable
{
    const CSS      = 'css';
    const JS = 'app';
    const SASS     = 'sass';
    const LESS     = 'less';
    const IMG      = 'img';
    const FONT     = 'fonts';
    const TEMPLATE = 'templates';

    protected $type;

    protected $path;

    protected $buildPath;

    protected $uri;

    protected $initialPath;

    /**
     * @param $type
     * @param $path
     * @param string $initialPath
     */
    public function __construct($type, $path, $initialPath = '')
    {
        $this->type        = $type;
        $this->path        = $path;
        $this->initialPath = $path;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     *
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBuildPath()
    {
        return $this->buildPath;
    }

    /**
     * @param mixed $buildPath
     *
     * @return self
     */
    public function setBuildPath($buildPath)
    {
        $this->buildPath = $buildPath;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     *
     * @return self
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInitialPath()
    {
        return $this->initialPath;
    }

    /**
     * @param mixed $initialPath
     */
    public function setInitialPath($initialPath)
    {
        $this->initialPath = $initialPath;
    }

    /**
     * @param $type
     * @return string
     */
    public static function getExtensionFromType($type)
    {
        if($type === self::JS) {
            return 'js';
        }

        return $type;
    }

    /**
     * @param $type
     * @return string
     */
    public static function getOutputFolder($type)
    {
        if($type === self::TEMPLATE) {
            return self::JS;
        }

        return $type;
    }

    /**
     * (PHP 5 >= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *       which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'path' => $this->path,
        ];
    }
}
