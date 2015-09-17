<?php namespace App\Libraries\Generator\Generators\Migration;
use App\Libraries\Generator\Generators\Code;

/******************************************************************************
 *
 * @package Myo 2
 * @copyright © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link http://www.versusmind.eu/
 *
 * @file Columns.php
 * @author LAHAXE Arnaud
 * @last-edited 17/09/2015
 * @description Columns
 *
 ******************************************************************************/

class Columns extends Code
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

            if ($field['nullable']) {
                $migration .= '->nullable()';
            }

            if($field['unsigned']) {
                $migration .= '->unsigned()';
            }

            if(!empty($field['default'])) {
                $migration .= sprintf('->default("%s")', $field['default']);
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