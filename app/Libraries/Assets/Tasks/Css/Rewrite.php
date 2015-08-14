<?php namespace App\Libraries\Assets\Tasks\Css;

use App\Libraries\Assets\Asset;
use App\Libraries\Assets\Collection;
use App\Libraries\Assets\Rewriter;
use League\Pipeline\StageInterface;

/**
 * Class Min
 *
 *
 *
 * @package App\Libraries\Assets\Tasks\Css
 * @author  LAHAXE Arnaud
 */
class Rewrite implements StageInterface
{

    /**
     * @param Collection $collection
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function process ($collection)
    {
        \Log::info('Assets::Css::Rewrite on collection ' . $collection->getCollectionId());

        foreach ($collection->getType(Asset::CSS) as $asset) {
            $writer = new Rewriter(file_get_contents($asset->getPath()));
            file_put_contents($asset->getPath(), $writer->process());
        }

        return $collection;
    }
}