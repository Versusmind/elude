<?php namespace App\Libraries\Assets\Tasks\Sass;

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
 * @file Compile.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Compile
 *
 ******************************************************************************/


use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use App\Libraries\SassCompiler;
use League\Pipeline\StageInterface;

/**
 * Class Sass
 *
 *
 *
 * @package App\Libraries\Assets\Tasks\Javascript
 *
 * @author  LAHAXE Arnaud
 */
class Compile implements StageInterface
{
    protected $compiler;

    public function __construct()
    {
        $this->compiler = new SassCompiler();
    }

    /**
     * @param Collection $collection
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     *
     * @return mixed
     */
    public function process($collection)
    {
        $newAssetsFiles = [];
        foreach ($collection->getType(Asset::SASS) as $asset) {

            $path = $asset->getPath();
            $pathInfo = pathinfo($path);
            $this->compiler->setImportPaths($pathInfo['dirname'] . DIRECTORY_SEPARATOR);

            $content    = $this->compiler->compile(file_get_contents($path));
            $outputFile = $collection->getTmpDirectory() . DIRECTORY_SEPARATOR . str_replace(DIRECTORY_SEPARATOR, '-', str_replace(base_path('resources/assets/'), '', $asset->getPath())) . '.css';
            file_put_contents($outputFile, $content);
            $newAssetsFiles[] = new Asset(Asset::CSS, $outputFile);
        }

        foreach (array_reverse($newAssetsFiles) as $asset) {
            $collection->prependType(Asset::CSS, $asset);
        }

        return $collection;
    }
}
