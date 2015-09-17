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
class BelongTo extends Code
{
    /**
     * @return string
     */
    public function generate()
    {
        $lines = [];
        foreach ($this->get('foreignKeys') as $foreignKey) {
            $belongToFunctions = '    /**' . "\n"
                . '    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo' . "\n"
                . '    */' . "\n"
                . '    public function %s()' . "\n"
                . '    {' . "\n"
                . '        return $this->belongsTo(\App\%s::class, "%s_id", "id");' . "\n"
                . '     }';


            $belongToFunctions = sprintf($belongToFunctions, $foreignKey, ucfirst($foreignKey), $foreignKey);

            $lines[] = $belongToFunctions;
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