<?php namespace App;

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
