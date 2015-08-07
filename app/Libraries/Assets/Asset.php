<?php
/**
 * User: LAHAXE Arnaud <alahaxe@boursorama.fr>
 * Date: 07/08/2015
 * Time: 12:31
 * FileName : Asset.php
 * Project : myo2
 */

namespace App\Libraries\Assets;

class Asset
{

    const CSS = 'css';
    const JS = 'js';
    const SASS = 'sass';
    const LESS = 'less';

    protected $type;

    protected $path;

    protected $buildPath;

    protected $uri;

    /**
     * Asset constructor.
     *
     * @param $type
     * @param $path
     */
    public function __construct($type, $path)
    {
        $this->type = $type;
        $this->path = $path;
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
}