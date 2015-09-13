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
 * @file Role.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Role
 *
 ******************************************************************************/

use App\Libraries\Validation\ValidationInterface;
use App\Libraries\Validation\ValidationTrait;
use Illuminate\Database\Eloquent\Model;
use App\Libraries\Acl\Exceptions\UnknownRoleFilter;
use App\Libraries\Acl\Interfaces\RoleInterface;

/**
 * Class Role
 *
 * @package    App\Models
 */
class Role extends Model implements RoleInterface, ValidationInterface
{

    use ValidationTrait;

    const FILTER_ACCESS = 'A';
    const FILTER_DENY   = 'D';
    const FILTER_REVOKE = 'R';

    /**
     * Mass fillable columns
     *
     * @var array
     */
    protected $fillable = ['name', 'filter'];

    /**
     * Validation rules
     *
     * @var array
     */
    protected static $rules = [
        'name' => 'required|min:3',
        'filter' => 'required|in:A,D,R',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'acl_roles';

    /**
     * Available values:
     *  A - allow access
     *  D - deny access
     *  R - revoke access
     * @var array
     */
    protected $filters = [self::FILTER_ACCESS, self::FILTER_DENY, self::FILTER_REVOKE];

    /**
     * User role permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'acl_role_permissions',
            'role_id',
            'permission_id'
        )->withTimestamps();
    }

    /**
     * Set special filter.
     * Available values:
     *  A - allow access
     *  D - deny access
     *  R - revoke access
     *
     * @param $filter
     * @throws UnknownRoleFilter
     */
    public function setFilter($filter)
    {
        $filter = (string) $filter;
        if (!in_array($filter, $this->filters)) {
            throw new UnknownRoleFilter("Unknown role filter: '{$filter}'");
        }

        $this->filter = $filter;
    }

    public function getFilter()
    {
        return $this->filter;
    }
}
