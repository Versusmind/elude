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

class BelongTo extends Code
{
    /**
     * @return string
     */
    public function generate()
    {
        $lines = [];
        foreach ($this->get('foreignKeys') as $foreignKey) {
            $migration = '            $table->integer("%s_id")->unsigned();' . "\n\n"
                .'            $table->foreign("%s_id")' . "\n"
                .'                ->references("id")->on("%s")' . "\n"
                .'                 ->onDelete("cascade");';


            $migration = sprintf($migration, $foreignKey, $foreignKey, str_plural($foreignKey));

            $lines[] = $migration;
        }

        return join("\n", $lines);
    }

    /**
     * @return array
     */
    public function options()
    {
        return [
            'foreignKeys'
        ];
    }
}