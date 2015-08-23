<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthContract;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements AuthContract
{
    use Authenticatable;

    protected $primaryKey = 'id';
    protected $table      = 'users';
    protected $fillable   = [
        'username',
        'email',
        'password',
    ];
    protected $hidden = ['password'];
}
