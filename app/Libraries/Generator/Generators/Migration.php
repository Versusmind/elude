<?php namespace App\Libraries\Generator\Generators;

/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 16/09/2015
 * Time: 21:41
 */
class Migration extends Code
{
    /**
     * @return string
     */
    public function generate()
    {
        $lines = [];
        foreach ($this->get('fields') as $field) {
            $migration = sprintf('$table->%s("%s")', $field['type'], $field['name']);
            if ($field['nullable']) {
                $migration .= '->nullable()';
            }
            $migration .= ';';

            $lines[] = $migration;
        }

        return join("\n            ", $lines);
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