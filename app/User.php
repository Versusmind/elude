<?php namespace App;

use App\Libraries\Acl\Interfaces\UserInterface;
use App\Libraries\Acl\Interfaces\UserRestrictionCapabilitiesInterface;
use App\Libraries\Acl\Traits\UserRestrictionCapabilities;
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
    protected $hidden = ['password', 'remember_token'];

    /**
     * @var array
     */
    protected $fillable = ['email', 'username', 'email', 'password'];

    protected $primaryKey = 'id';

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

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        // TODO: Implement getRememberToken() method.
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        // TODO: Implement getEmailForPasswordReset() method.
    }


    public function isSuperAdmin()
    {
        return false;
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
