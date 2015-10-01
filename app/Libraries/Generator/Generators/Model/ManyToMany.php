<?php namespace App\Libraries\Generator\Generators\Model;

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
 * @file BelongTo.php
 * @author LAHAXE Arnaud
 * @last-edited 17/09/2015
 * @description BelongTo
 *
 ******************************************************************************/
class ManyToMany extends Code
{
    /**
     * @return string
     */
    public function generate()
    {
        $lines = [];
        foreach ($this->get('foreignKeys') as $foreignKey) {
            $line = '    /**' . "\n"
                . '    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo' . "\n"
                . '    */' . "\n"
                . '    public function %s()' . "\n"
                . '    {' . "\n"
                . '        return $this->belongsToMany(\App\%s::class);' . "\n"
                . '     }';


            $line = sprintf($line, str_plural($foreignKey), ucfirst($foreignKey), $foreignKey);

            $lines[] = $line;
        }

        return join("\n\n", $lines);
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