<?php namespace App\Libraries\Assets\Tasks\Javascript;

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

    public function process($payload)
    {
        echo "Js MIN <br/>";

        return $payload;
    }
}