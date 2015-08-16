<?php namespace App\Libraries\Assets;

class Asset implements \JsonSerializable
{

    const CSS = 'css';
    const JS = 'js';
    const SASS = 'sass';
    const LESS = 'less';
    const IMG = 'img';
    const FONT = 'fonts';
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
    public function __construct ($type, $path, $initialPath = '')
    {
        $this->type = $type;
        $this->path = $path;
        $this->initialPath = $path;
    }

    /**
     * @return mixed
     */
    public function getType ()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return self
     */
    public function setType ($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath ()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     *
     * @return self
     */
    public function setPath ($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBuildPath ()
    {
        return $this->buildPath;
    }

    /**
     * @param mixed $buildPath
     *
     * @return self
     */
    public function setBuildPath ($buildPath)
    {
        $this->buildPath = $buildPath;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUri ()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     *
     * @return self
     */
    public function setUri ($uri)
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
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *       which is a value of any type other than a resource.
     */
    function jsonSerialize ()
    {
        return [
            'type' => $this->type,
            'path' => $this->path
        ];
    }
}