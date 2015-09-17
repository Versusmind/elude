<?php namespace App\Libraries\Generator\Generators\Api;

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
 * @file InputParameters.php
 * @author LAHAXE Arnaud
 * @last-edited 17/09/2015
 * @description InputParameters
 *
 ******************************************************************************/

class InputParameters extends Code
{
    /**
     * @return string
     */
    public function generate()
    {
        $lines = [];
        foreach ($this->get('fields') as $field) {
            $lines[] = sprintf('* @apiParam {%s} %s %s.', $field['apiType'], $field['name'], ucfirst($field['name']));
        }

        return join("\n         ", $lines);
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