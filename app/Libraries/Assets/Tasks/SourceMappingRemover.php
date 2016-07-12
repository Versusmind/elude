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
 * @file Copy.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Copy
 *
 ******************************************************************************/


use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;

/**
 * Date: 12/06/2016
 * Time: 12:13
 * FileName : Concat.php
 * Project : myo2
 * @todo this task must be delete when we can handle correct sourceMapping
 */
class SourceMappingRemover implements StageInterface
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
        \Log::debug('Assets::SourceMappingRemover on collection ' . $collection->getCollectionId());

        foreach ($collection->getType($this->type) as $asset) {

            file_put_contents($asset->getPath(), preg_replace('~(//|/[*])[#@]\s(source(?:Mapping)?URL)=\s*([aA-zZ0-9:.-]+)\s*([*]/)?~', '', file_get_contents($asset->getPath())));
        }

        return $collection;
    }
}
