<?php namespace App\Libraries\Assets\Tasks\Sass;

use App\Libraries\Assets\Asset;
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
        echo "Sass compile <br/>";
        /** @var Collection $collection */
        $collection = $payload[0];
        foreach($collection->getType(Asset::SASS) as $asset) {


            $collection->prependType(Asset::CSS, new Asset(Asset::CSS, $asset->getPath() . '.css'));
            echo $asset->getPath() . '<br/>';
        }

        return $payload;
    }
}