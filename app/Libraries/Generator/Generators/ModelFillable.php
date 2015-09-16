<?php namespace App\Libraries\Generator\Generators;

/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 16/09/2015
 * Time: 21:41
 */
class ModelFillable extends Code
{
    /**
     * @return string
     */
    public function generate()
    {
        $lines = [];
        foreach ($this->get('fields') as $field) {
            $lines[] = sprintf("'%s'", $field['name']);
        }

        return join(",\n        ", $lines);
    }

    /**
     * @return array
     */
    public function options()
    {
        return [
            'fields'
        ];
    }
}