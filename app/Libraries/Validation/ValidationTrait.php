<?php namespace App\Libraries\Validation;

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
 * @file ValidationTrait.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description ValidationTrait
 *
 ******************************************************************************/

use Illuminate\Support\MessageBag;

trait ValidationTrait
{
    /**
     * @var MessageBag
     */
    public $errors;

    /**
     * @return array
     */
    public function getRules()
    {
        return isset(self::$rules)?self::$rules:[];
    }
}