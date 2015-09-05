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
 * @file AttributeNotExist.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description AttributeNotExist
 *
 ******************************************************************************/


use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;

class AttributeNotExist extends ModelNotValid
{
    public function __construct(Model $model, $attributes)
    {

        parent::__construct(new MessageBag([$attributes . 'does not exist or is not fillable for class ' . get_class($model)]));
    }
}
