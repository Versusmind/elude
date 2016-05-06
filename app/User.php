<?php namespace App;

use App\Libraries\Acl\Interfaces\UserInterface;
use App\Libraries\Acl\Interfaces\UserRestrictionCapabilitiesInterface;
use App\Libraries\Acl\Traits\UserRestrictionCapabilities;
use App\Libraries\Validation\ValidationInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @package    App\Models
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract, UserInterface, ValidationInterface, UserRestrictionCapabilitiesInterface
{

    use Authenticatable, CanResetPassword, UserRestrictionCapabilities;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'is_super_admin'];

    /**
     * @var array
     */
    protected $fillable = ['email', 'username', 'email', 'password'];

    protected $primaryKey = 'id';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'lost_password_token_created_at'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    protected static $rules = [
        'email'    => 'required|email|unique:users,email',
        'username' => 'required|min:3|unique:users,username',
        'password' => 'required|min:6',
    ];

    /**
     * User personal permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'acl_user_permissions',
            'user_id',
            'permission_id'
        )->withTimestamps();
    }

    /**
     * Get user roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'acl_user_roles',
            'user_id',
            'role_id'
        )->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRules()
    {

        if (!$this->exists) {

            return self::$rules;
        }

        $rules = self::$rules;
        $rules['email'] .= ',' . $this->id;
        $rules['username'] .= ',' . $this->id;

        return $rules;
    }

    /**
     * @param \App\User $testedUser
     * @param array     $parameters
     *
     * @return bool
     */
    public function isUserAllow(User $testedUser, array $parameters = [])
    {
        return $testedUser->id === $this->id;
    }

    public function isSuperAdmin()
    {
        return $this->is_super_admin;
    }
    
    /**
     * @return string
     */
    public function getUserIdFields()
    {
        return $this->getKeyName();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this;
    }
}
