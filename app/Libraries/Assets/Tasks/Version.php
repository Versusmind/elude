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
 * @file Version.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Version
 *
 ******************************************************************************/


use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;

/**
 * Class Version
 *
 * @author  LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 *
 * @package App\Libraries\Assets\Tasks
 */
class Version implements StageInterface
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
     * @return mixed
     */
    public function process($collection)
    {

        $outputDirectory = $collection->getOutputDirectory() . DIRECTORY_SEPARATOR . $this->type . DIRECTORY_SEPARATOR;
        if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0777, true)) {
            throw new \RuntimeException('Fail to create ' . $outputDirectory);
        }

        $newAssetsFiles = [];
        foreach ($collection->getType($this->type) as $asset) {
            copy($asset->getPath(), $outputDirectory . $collection->getCollectionId() . '.' . $this->type);
            $newAssetsFiles[] = new Asset($this->type, $outputDirectory . $collection->getCollectionId() . '.' . $this->type);
        }

        $collection->setType($this->type, $newAssetsFiles);

        $collection->writeVersion($newAssetsFiles, config('assets.concat'));

        return $collection;
    }
}
