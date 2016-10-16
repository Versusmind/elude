<?php namespace App\Libraries\Criterias;

use App\Libraries\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Criteria {

    /**
     * @author LAHAXE Arnaud
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $model
     * @param \App\Libraries\Repository             $repository
     *
     * @return mixed
     */
    public abstract function apply(Builder $query, Model $model, Repository $repository);
}