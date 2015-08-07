<?php
/**
 * User: LAHAXE Arnaud <alahaxe@boursorama.fr>
 * Date: 07/08/2015
 * Time: 12:33
 * FileName : Group.php
 * Project : myo2
 */

namespace App\Libraries\Assets;

class Collection
{

    private $types = [Asset::CSS, Asset::JS, Asset::SASS, Asset::LESS];

    protected $assets;

    /**
     * Collection constructor.
     *
     * @param $assets
     * [
     *      'css' => [
     *          '/path/file.css',
     *          '/path/file2.css'
     *      ],
     *      'js' => [
     *          '/path/file.js',
     *          '/path/file2.js'
     *      ],
     *      'sass' => [
     *          '/path/file.scss',
     *          '/path/file2.scss'
     *      ] ...
     * ]
     */
    public function __construct($assets)
    {
        $this->assets = [];

        foreach($this->types as $type) {
            if(!isset($assets[$type])) {
                continue;
            }

            foreach ($assets[$type] as $path) {
                $this->assets[$type][] = new Asset($type, $path);
            }
        }
    }

    public function getType($type)
    {
        if(isset($this->assets[$type]) && is_array($this->assets[$type])) {
            return $this->assets[$type];
        }

        return [];
    }

    public function prependType($type, Asset $asset) {
        array_unshift($this->assets[$type], $asset);
    }

    public function appendType($type, Asset $asset) {
        $this->assets[$type][] = $asset;
    }
}