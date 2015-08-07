<?php namespace App\Libraries\Assets\Tasks\Javascript;

use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;


class Html implements StageInterface
{

    /**
     * @param Collection $collection
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function process ($collection)
    {
        \Log::info('Assets::Javascript::Html on collection ' . $collection->getCollectionId());

        $result = '';

        $outputDirectory = base_path('public') . DIRECTORY_SEPARATOR;

        foreach ($collection->getType(Asset::JS) as $asset) {

            $result .= '<script src="' . str_replace($outputDirectory, DIRECTORY_SEPARATOR, $asset->getPath()) . '"></script>';
        }

        return $result;
    }
}
