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
 * @file Html.php
 * @author LAHAXE Arnaud
 * @last-edited 18/08/15
 * @description Html
 *
 ******************************************************************************/

use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;


class Html implements StageInterface
{

    /**
     * @param Collection $collection
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return string
     */
    public function process($collection)
    {
        \Log::info('Assets::Css::Html on collection ' . $collection->getCollectionId());
        $result = '';

        if (!config('assets.concat') && $collection->getGroupName()) {
            $result .= "<!-- " . $collection->getGroupName() . "-->" . "\n";
        }

        $outputDirectory = base_path('public') . DIRECTORY_SEPARATOR;

        $subfolder = env('SUBFOLDER_INSTALLATION', false);
        if ($subfolder) {
            $subfolder .= '/';
        } else {
            $subfolder = '';
        }

        foreach ($collection->getType(Asset::CSS) as $asset) {

            $result .= '<link rel="stylesheet" type="text/css" href="' . $subfolder . str_replace($outputDirectory, DIRECTORY_SEPARATOR, $asset->getPath()) . '">' . "\n";
        }

        if (!config('assets.concat') && $collection->getGroupName()) {
            $result .= "<!-- /" . $collection->getGroupName() . "-->" . "\n\n";
        }

        return $result;
    }
}
