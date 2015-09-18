<?php namespace App\Libraries\Generator\Generators\Route;

use App\Libraries\Generator\Generators\Code;

/**
 * User: LAHAXE Arnaud <alahaxe@boursorama.fr>
 * Date: 18/09/2015
 * Time: 13:35
 * FileName : ForeignKeys.php
 * Project : myo2
 */
class ForeignKeys extends Code
{

    /**
     * @return string
     */
    public function generate()
    {
        $lines = [];

        $relationRouteCreate = "\n" . '        $app->post("%s/{id}/%s/{id%s}", ["as" => "%s.%s.store", "uses" => \App\Http\Controllers\Api\%s::class . "@%sStore"]);';
        $relationRouteDelete = "\n" . '        $app->delete("%s/{id}/%s/{id%s}", ["as" => "%s.%s.destroy", "uses" => \App\Http\Controllers\Api\%s::class . "@%sDestroy"]);' . "\n";

        $model = strtolower($this->get('model'));
        $modelPlural = str_plural($model);
        foreach($this->get('foreignKeys') as $foreignKey) {
            $newRoute = sprintf($relationRouteCreate, $modelPlural, strtolower(str_plural($foreignKey)), ucfirst($foreignKey), $modelPlural, strtolower(str_plural($foreignKey)), ucfirst($model), strtolower($foreignKey));
            $newRoute .= sprintf($relationRouteDelete, $modelPlural, strtolower(str_plural($foreignKey)), ucfirst($foreignKey), $modelPlural, strtolower(str_plural($foreignKey)), ucfirst($model), strtolower($foreignKey));

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