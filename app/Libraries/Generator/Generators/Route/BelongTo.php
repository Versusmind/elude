<?php namespace App\Libraries\Generator\Generators\Route;

use App\Libraries\Generator\Generators\Code;

/**
 * User: LAHAXE Arnaud
 * Date: 18/09/2015
 * Time: 13:35
 * FileName : ForeignKeys.php
 * Project : myo2
 */
class BelongTo extends Code
{

    /**
     * @return string
     */
    public function generate()
    {
        $lines = [];

        $relationRouteCreate = "\n" . '        $app->put("%s/{id}/%s/{id%s}", ["as" => "%s.%s.store", "uses" => \App\Http\Controllers\Api\%s::class . "@%sUpdate"]);';

        $model = strtolower($this->get('model'));
        $modelPlural = str_plural($model);
        foreach($this->get('foreignKeys') as $foreignKey) {
            $newRoute = sprintf($relationRouteCreate, $modelPlural, strtolower(str_plural($foreignKey)), ucfirst($foreignKey), $modelPlural, strtolower(str_plural($foreignKey)), ucfirst($model), strtolower($foreignKey));

            $lines[] = $newRoute;
        }

        return join('', $lines);
    }

    /**
     * Return the list of required parameters keys
     *
     * @return array
     */
    public function options()
    {
        return [
            'model',
            'foreignKeys'
        ];
    }
}