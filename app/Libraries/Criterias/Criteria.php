<?php namespace App\Libraries\Criterias;

use App\Libraries\Repository;
use Illuminate\Database\Eloquent\Model;

abstract class Criteria {

    /**
     * @author LAHAXE Arnaud
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \App\Libraries\Repository           $repository
     *
     * @return mixed
     */
    public abstract function apply(Model $model, Repository $repository);
}