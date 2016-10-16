<?php namespace App\Libraries\Acl\Exceptions;

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
 * @file ModelNotValid.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description ModelNotValid
 *
 ******************************************************************************/

use Exception;
use Illuminate\Support\MessageBag;


class ModelNotValid extends \Exception
{
    /**
     * @var MessageBag
     */
    protected $errors;

    public function __construct(MessageBag $errors)
    {
        $this->errors = $errors;

        parent::__construct($errors->toJson());
    }

    /**
     * @return MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
