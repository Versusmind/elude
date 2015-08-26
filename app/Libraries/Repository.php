<?php namespace App\Libraries;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

abstract class Repository
{

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
    }

    /**
     * @param $attributes
     *
     * @return Model
     */
    public function create($attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     *
     * @return Model | null
     */
    public function find($id)
    {

        return $this->model->find($id);
    }

    /**
     * @param Model $model
     * @param       $attributes
     *
     * @return Model
     */
    public function update(Model $model, $attributes)
    {
        foreach ($attributes as $key => $value) {
            $model->$key = $value;
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
        if($nbItemsPerPage < 1 || $nbItemsPerPage > 100) {
            $nbItemsPerPage = 15;
        }

        if($page < 1) {
            $page = 1;
        }

        if ($paginate) {
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });

            return $this->model->paginate($nbItemsPerPage);
        }

        return $this->model->all();
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
        if($nbItemsPerPage < 1 || $nbItemsPerPage > 100) {
            $nbItemsPerPage = 15;
        }

        if($page < 1) {
            $page = 1;
        }

        if ($paginate) {
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });

            return $this->model->where($where)->paginate($nbItemsPerPage);
        }

        return $this->model->where($where)->get();
    }

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }
}