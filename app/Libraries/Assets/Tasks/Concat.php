<?php namespace App\Libraries\Assets\Tasks;

use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use League\Pipeline\StageInterface;

/**
 * Class Concat
 *
 * @author  LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * @package App\Libraries\Assets\Tasks
 */
class Concat implements StageInterface
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
        \Log::info('Assets::Concat on collection ' . $collection->getCollectionId());

        $outputFile = $collection->getTmpDirectory() . DIRECTORY_SEPARATOR . $collection->getCollectionId() . '.' . $this->type;

        foreach ($collection->getType($this->type) as $asset) {
            file_put_contents($outputFile, file_get_contents($asset->getPath()) . "\n", FILE_APPEND | LOCK_EX);
        }

        $collection->setType($this->type, [
            new Asset($this->type, $outputFile)
        ]);

        return $collection;
    }
}