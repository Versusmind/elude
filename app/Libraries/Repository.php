<?php namespace App\Libraries;

/******************************************************************************
 *
 * @package     Myo 2
 * @copyright   © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link        http://www.versusmind.eu/
 *
 * @file        Repository.php
 * @author      LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description Repository
 *
 ******************************************************************************/

use App\Libraries\Acl\Exceptions\AttributeNotExist;
use App\Libraries\Acl\Exceptions\ModelNotValid;
use App\Libraries\Criterias\Criteria;
use App\Libraries\Criterias\Interfaces\CriteriaInterface;
use App\Libraries\Validation\ValidationInterface;
use Clockwork\Facade\Clockwork;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

abstract class Repository implements CriteriaInterface
{

    /**
     * @var array
     */
    protected $criterias;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $modelClass;

    /**
     * Repository constructor.
     *
     * @param $modelClass
     */
    public function __construct($modelClass)
    {
        $this->model      = new $modelClass;
        $this->modelClass = $modelClass;
        $this->criterias  = [];
    }

    /**
     * @param Model $model
     * @param bool|true $validate
     * @return Model
     * @throws ModelNotValid
     */
    public function create(Model $model, $validate = true)
    {

        if ($model instanceof ValidationInterface) {
            if ($validate) {
                $this->validate($model);
            }
        }

        $model->save();

        return $model;
    }

    /**
     * @param $id
     *
     * @return Model | null
     */
    public function find($id)
    {
        $query = $this->applyCriterias($this->makeQuery());

        return $query->find($id);
    }

    /**
     * @param Model     $model
     * @param           $attributes
     * @param bool|true $validate
     *
     * @return Model
     * @throws AttributeNotExist
     * @throws ModelNotValid
     */
    public function update(Model $model, $attributes = array(), $validate = true)
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $model->getFillable(), true)) {
                $model->$key = $value;
            } else {
                throw new AttributeNotExist($model, $key);
            }
        }

        if ($model instanceof ValidationInterface) {
            if ($validate) {
                $this->validate($model);
            }
        }

        $model->save();

        return $model;
    }

    /**
     * @param Model $model
     *
     * @return boolean
     */
    public function delete(Model $model)
    {
        return $model->delete();
    }

    /**
     * @param bool|false $paginate
     * @param int        $nbItemsPerPage
     * @param int        $page
     *
     * @return Collection | Paginator
     */
    public function all($paginate = false, $nbItemsPerPage = 15, $page = 1)
    {
        if ($nbItemsPerPage < 1 || $nbItemsPerPage > 100) {
            $nbItemsPerPage = 15;
        }

        if ($page < 1) {
            $page = 1;
        }

        $query = $this->makeQuery();

        $this->applyCriterias($query);
        if ($paginate) {
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });

            return $query->paginate($nbItemsPerPage);
        }

        return $query->where([])->get();
    }

    /**
     * @param array      $where
     * @param bool|false $paginate
     * @param int        $nbItemsPerPage
     * @param int        $page
     *
     * @return Collection | Paginator
     */
    public function where(array $where, $paginate = false, $nbItemsPerPage = 15, $page = 1)
    {
        if ($nbItemsPerPage < 1 || $nbItemsPerPage > 100) {
            $nbItemsPerPage = 15;
        }

        if ($page < 1) {
            $page = 1;
        }

        $query = $this->applyCriterias($this->makeQuery());

        if ($paginate) {
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });

            return $query->where($where)->paginate($nbItemsPerPage);
        }


        return $query->where($where)->get();
    }

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }

    /**
     * @param ValidationInterface $model
     *
     * @throws ModelNotValid
     */
    protected function validate(ValidationInterface $model)
    {
        $timelineKey = uniqid('validation_');
        Clockwork::startEvent($timelineKey, 'Validation of model ' . get_class($model));

        $modelArray = $model->toArray();
        // add hiddens fields to the array for validation
        foreach ($model->getHidden() as $hidden) {
            $modelArray[$hidden] = $model->{$hidden};
        }

        $validator = Validator::make($modelArray, $model->getRules());
        if ($validator->fails()) {
            Clockwork::endEvent($timelineKey);

            $model->setErrors($validator->errors());

            throw new ModelNotValid($validator->errors());
        }

        Clockwork::endEvent($timelineKey);
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder|mixed
     */
    public function  applyCriterias(Builder $query)
    {

        foreach ($this->criterias as $criteria) {
            if ($criteria instanceof Criteria) {
                $query = $criteria->apply($query, $this->model, $this);
            }
        }

        return $query;
    }

    /**
     * @author LAHAXE Arnaud
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function makeQuery()
    {
        return $this->model->query();
    }

    /**
     * @author LAHAXE Arnaud
     *
     *
     * @return array
     */
    public function getCriterias()
    {
        return $this->criterias;
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param \App\Libraries\Criterias\Criteria $criteria
     *
     * @return $this
     */
    public function addCriteria(Criteria $criteria)
    {
        $this->criterias[] = $criteria;

        return $this;
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param array $criterias
     *
     * @return $this
     */
    public function setCriterias(array $criterias)
    {
        $this->criterias = $criterias;

        return $this;
    }

    /**
     * @author LAHAXE Arnaud
     *
     *
     * @return $this
     */
    public function clearCriterias()
    {
        $this->setCriterias([]);

        return $this;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }
}