<?php namespace App\Libraries\Criterias;

use App\Libraries\Repository;
use Illuminate\Database\Eloquent\Model;

class User extends Criteria
{

    /**
     * @var \App\User
     */
    protected $user;

    /**
     * @param \App\User $user
     */
    public function __construct(\App\User $user)
    {
        $this->user = $user;
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \App\Libraries\Repository           $repository
     *
     * @return mixed
     */
    public function apply(Model $model, Repository $repository)
    {
        return $model->where($model->getUserIdFields(), $this->user->getKey());
    }
}