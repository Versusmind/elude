<?php namespace App\Libraries\Assets\Tasks;

use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;

/**
 * User: LAHAXE Arnaud <alahaxe@boursorama.fr>
 * Date: 07/08/2015
 * Time: 12:13
 * FileName : Concat.php
 * Project : myo2
 */
class Copy implements StageInterface
{

    protected $type;

    function __construct ($type)
    {
        $this->type = $type;
    }

    /**
     * @param Collection $collection
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return Collection|mixed
     */
    public function process ($collection)
    {
        \Log::info('Assets::Copy on collection ' . $collection->getCollectionId());

        $outputDirectory = $collection->getOutputDirectory() . $this->type . DIRECTORY_SEPARATOR;
        if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0777, TRUE)) {
            throw new \RuntimeException('Fail to create ' . $outputDirectory);
        }

        $newAssetsFiles = [];
        foreach ($collection->getType($this->type) as $asset) {
            // file is in tmp folder
            if (strpos($asset->getPath(), $collection->getTmpDirectory()) !== FALSE) {
                $relativePath = str_replace($collection->getTmpDirectory(), '', $asset->getPath());
            // file is in bower folder
            } elseif (strpos($asset->getPath(), $collection->getBowerDirectory()) !== FALSE) {
                $relativePath = str_replace($collection->getBowerDirectory(), '', $asset->getPath());
                if($asset->getType() === Asset::FONT) {
                    $relativePath = last(explode('/', $relativePath));
                }
            // file is resource/assets folder (no other case)
            } else {
                $relativePath = str_replace(config('assets.assetsDirectory') . DIRECTORY_SEPARATOR . $this->type . DIRECTORY_SEPARATOR, '', $asset->getPath());
            }

            if (strpos($relativePath, '/') !== FALSE) {
                $subfolders = join('/', explode(DIRECTORY_SEPARATOR, $relativePath, -1));

                if (!is_dir($outputDirectory . $subfolders)) {
                    if (!mkdir($outputDirectory . $subfolders, 0777, TRUE)) {
                        throw new \RuntimeException('Cannot create ' . $outputDirectory . $subfolders . ' directory');
                    }
                }
            }

            copy($asset->getPath(), $outputDirectory . $relativePath);
            $asset->setPath($outputDirectory . $relativePath);

            $newAssetsFiles[] = $asset;
        }

        $collection->setType($this->type, $newAssetsFiles);

        return $collection;
    }
}