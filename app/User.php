<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthContract;

class User extends Model implements AuthContract
{

    use Authenticatable;

    protected $table = 'users';
    protected $fillable = [
        'username',
        'email',
        'password'
    ];
    protected $hidden = ['password'];

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return 'username';
    }

}