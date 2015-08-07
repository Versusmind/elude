<?php namespace App\Libraries\Assets\Tasks;

use League\Pipeline\StageInterface;

/**
 * User: LAHAXE Arnaud <alahaxe@boursorama.fr>
 * Date: 07/08/2015
 * Time: 12:13
 * FileName : Concat.php
 * Project : myo2
 */
class Concat implements StageInterface
{

    public function process($payload)
    {
        echo "concat <br/>";

        return $payload;
    }
}