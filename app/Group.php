<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Acl\Interfaces\GroupInterface;

/**
 * Class Group
 *
 * @package    App\Models
 */
class Group extends Model implements GroupInterface, ValidationInterface
{

    use ValidationTrait;

    /**
     * Mass fillable columns
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'acl_groups';

    /**
     * Validation rules
     *
     * @var array
     */
    protected static $rules = [
        'name' => 'required|min:3'
    ];

    /**
     * User group permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'acl_group_permissions',
            'group_id',
            'permission_id'
        )->withPivot('actions')->withTimestamps();
    }

    /**
     * Get group roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'acl_group_roles',
            'group_id',
            'role_id'
        )->withTimestamps();
    }
}
