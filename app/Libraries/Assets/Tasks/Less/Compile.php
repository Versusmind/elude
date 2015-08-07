<?php namespace App\Libraries\Assets\Tasks\Less;

use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;

/**
 * Class Sass
 *
 *
 *
 * @package App\Libraries\Assets\Tasks\Javascript
 * @author  LAHAXE Arnaud
 */
class Compile implements StageInterface
{

    protected $compiler;

    function __construct ()
    {
        $this->compiler = new \lessc();
    }


    /**
     * @param Collection $collection
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function process ($collection)
    {
        \Log::info('Assets::Less::Compile on collection ' . $collection->getCollectionId());

        $newAssetsFiles = [];
        foreach ($collection->getType(Asset::LESS) as $asset) {
            $outputFile = $collection->getTmpDirectory() . DIRECTORY_SEPARATOR . str_replace(DIRECTORY_SEPARATOR, '-', str_replace(base_path('resources/assets/'), '', $asset->getPath())) . '.css';
            $this->compiler->checkedCompile($asset->getPath(), $outputFile);
            $newAssetsFiles [] = new Asset(Asset::CSS, $outputFile);
        }

        foreach(array_reverse($newAssetsFiles) as $asset) {
            $collection->prependType(Asset::CSS, $asset);
        }

        return $collection;
    }
}