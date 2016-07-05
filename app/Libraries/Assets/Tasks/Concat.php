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
 * @file Concat.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Concat
 *
 ******************************************************************************/


use App\Facades\Assets;
use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;

/**
 * Class Concat
 *
 * @author  LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 *
 * @package App\Libraries\Assets\Tasks
 */
class Concat implements StageInterface
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
        \Log::debug('Assets::Concat on collection ' . $collection->getCollectionId());

        $outputFile = $collection->getTmpDirectory() . DIRECTORY_SEPARATOR . $collection->getCollectionId() . '.' . Asset::getExtensionFromType($this->type);

        foreach ($collection->getType($this->type) as $asset) {
            file_put_contents($outputFile, file_get_contents($asset->getPath()) . "\n", FILE_APPEND | LOCK_EX);
        }

        $collection->setType($this->type, [
            new Asset($this->type, $outputFile),
        ]);

        return $collection;
    }
}
