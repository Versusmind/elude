<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Acl\Interfaces\PermissionInterface;

/**
 * Class Permission
 *
 * @package Signes\Acl\Model
 */
class Permission extends Model implements PermissionInterface
{

    /**
     * Mass fillable columns
     *
     * @var array
     */
    protected $fillable = array('area', 'permission', 'action', 'description');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'acl_permissions';

    public function getAction()
    {
        return $this->action;
    }
}
