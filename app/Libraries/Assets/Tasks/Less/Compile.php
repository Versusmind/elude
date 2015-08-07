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

    public function process($payload)
    {
        echo "Less compile <br/>";
        /** @var Collection $collection */
        $collection = $payload[0];
        foreach($collection->getType(Asset::LESS) as $asset) {
            
            $collection->prependType(Asset::CSS, new Asset(Asset::CSS, $asset->getPath() . '.css'));
            echo $asset->getPath() . '<br/>';
        }


        return $payload;
    }
}