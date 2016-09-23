<?php namespace App\Libraries\Generator\Generators\Route;

use App\Libraries\Generator\Generators\Code;

/**
 * User: LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 18/09/2015
 * Time: 13:35
 * FileName : Resource.php
 * Project : myo2
 */
class Resource extends Code
{

    /**
     * @return string
     */
    public function generate()
    {

        $route = "\n        " . '$app->resource("%s", \App\Http\Controllers\Api\%s::class);';

        $model = $this->get('model');
        $modelPlural = str_plural($model);

        return sprintf($route, $modelPlural, $model);
    }

    /**
     * Return the list of required parameters keys
     *
     * @return array
     */
    public function options()
    {
        return [
            'model'
        ];
    }
}