<?php namespace App\Libraries\Assets\Tasks\Css;

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
 * @file Rewrite.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Rewrite
 *
 ******************************************************************************/

use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;

/**
 * Class Min
 *
 *
 *
 * @package App\Libraries\Assets\Tasks\Css
 *
 * @author  LAHAXE Arnaud
 */
class Rewrite implements StageInterface
{
    /**
     * @param Collection $collection
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     *
     * @return mixed
     */
    public function process($collection)
    {
        \Log::info('Assets::Css::Rewrite on collection ' . $collection->getCollectionId());

        foreach ($collection->getType(Asset::CSS) as $asset) {
            file_put_contents($asset->getPath(), preg_replace_callback('`url\((.*?)\)`s', function ($matches) {
                if (strpos($matches[0], '://') !== false) {
                    return $matches[0];
                }
                $matches[1] = str_replace(['"', "'", '../'], '', $matches[1]);

                return "url(" . env('SUBFOLDER_INSTALLATION', '') . "/assets/" . $matches[1] . ")";

            }, file_get_contents($asset->getPath())));
        }

        return $collection;
    }
}
