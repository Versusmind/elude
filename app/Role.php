<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Signes\Acl\Exception\UnknownRoleFilter;
use Signes\Acl\RoleInterface;

/**
 * Class Role
 *
 * @package    App\Models
 */
class Role extends Model implements RoleInterface
{

    /**
     * Mass fillable columns
     *
     * @var array
     */
    protected $fillable = ['name', 'filter'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'acl_roles';

    /**
     * Available values:
     *  A - allow access to everything
     *  D - deny access to everything
     *  R - revoke access to resource
     *
     * @var array
     */
    protected $filters = ['A', 'D', 'R'];

    /**
     * User role permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getPermissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'acl_role_permissions',
            'role_id',
            'permission_id'
        )->withPivot('actions')->withTimestamps();
    }

    /**
     * Set special filter.
     * Available values:
     *  A - allow access to everything
     *  D - deny access to everything
     *  R - revoke access to resource
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

}
