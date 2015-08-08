<?php namespace App\Libraries\Assets\Tasks\Css;

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
    public function process ($collection)
    {
        \Log::info('Assets::Css::Html on collection ' . $collection->getCollectionId());
        $result = '';

        if(!config('assets.concat') && $collection->getGroupName()) {
            $result .=  "<!-- " . $collection->getGroupName() . "-->" . "\n";
        }

        $outputDirectory = base_path('public') . DIRECTORY_SEPARATOR;

        foreach ($collection->getType(Asset::CSS) as $asset) {

            $result .= '<link rel="stylesheet" type="text/css" href="' . str_replace($outputDirectory, DIRECTORY_SEPARATOR, $asset->getPath()) . '">'  . "\n";
        }

        if(!config('assets.concat') && $collection->getGroupName()) {
            $result .=  "<!-- /" . $collection->getGroupName() . "-->" . "\n\n";
        }

        return $result;
    }
}
