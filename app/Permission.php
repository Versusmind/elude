<?php namespace App;

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
 * @file Permission.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Permission
 *
 ******************************************************************************/

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Acl\Interfaces\PermissionInterface;

/**
 * Class Permission
 *
 * @package Signes\Acl\Model
 */
class Permission extends Model implements PermissionInterface, ValidationInterface
{

    use ValidationTrait;

    /**
     * Mass fillable columns
     *
     * @var array
     */
    protected $fillable = array('area', 'permission', 'description');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'acl_permissions';

    /**
     * Validation rules
     *
     * @var array
     */
    protected static $rules = [
        'area' => 'required|min:3',
        'permission' => 'required|min:3',
        'description' => 'min:3',
    ];

    public function getAction()
    {
        return $this->area . '.' . $this->permission;
    }
}
