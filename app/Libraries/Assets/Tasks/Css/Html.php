<?php namespace App\Libraries\Assets\Tasks\Css;

use App\Libraries\Assets\Asset;
use League\Pipeline\StageInterface;


class Html implements StageInterface
{

    public function process($payload)
    {
        echo "Css html <br/>";

        foreach($payload[0]->getType(Asset::CSS) as $asset) {
            echo $asset->getPath() . '<br/>';
        }

        return $payload;
    }
}
