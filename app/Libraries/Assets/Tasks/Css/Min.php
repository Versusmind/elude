<?php namespace App\Libraries\Assets\Tasks\Css;

use League\Pipeline\StageInterface;

/**
 * Class Min
 *
 *
 *
 * @package App\Libraries\Assets\Tasks\Css
 * @author  LAHAXE Arnaud
 */
class Min implements StageInterface
{

    public function process($payload)
    {
        echo "CSS MIN <br/>";

        foreach($payload[0]->getType(Asset::CSS) as $asset) {
            echo $asset->getPath() . '<br/>';
        }

        return $payload;
    }
}