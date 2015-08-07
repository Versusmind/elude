<?php namespace App\Libraries\Assets\Tasks\Javascript;

use League\Pipeline\StageInterface;


class Html implements StageInterface
{

    public function process($payload)
    {
        echo "Js html <br/>";

        return $payload;
    }
}
