<?php namespace App\Libraries\Assets\Tasks\Javascript;

use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;

/**
 * Class Min
 *
 *
 *
 * @package App\Libraries\Assets\Tasks\Javascript
 * @author  LAHAXE Arnaud
 */
class Min implements StageInterface
{

    /**
     * @param Collection $collection
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function process ($collection)
    {
        \Log::info('Assets::Javascript::Min on collection ' . $collection->getCollectionId());

        $newAssets = [];
        foreach ($collection->getType(Asset::JS) as $asset) {
            $outputFile = $collection->getTmpDirectory() . DIRECTORY_SEPARATOR . md5($asset->getPath()) . '.min.js';
            $packer     = new \JavaScriptPacker(file_get_contents($asset->getPath()), 'Normal', TRUE, FALSE);
            $packed     = $packer->pack();
            file_put_contents($outputFile, $packed);
            $newAssets[] = new Asset(Asset::JS, $outputFile);
        }
        $collection->setType(Asset::JS, $newAssets);

        return $collection;
    }
}