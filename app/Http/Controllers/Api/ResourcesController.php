<?php namespace App\Http\Controllers\Api;

use App\Libraries\Acl\Exceptions\ModelNotValid;
use App\Libraries\Acl\Interfaces\UserRestrictionInterface;
use App\Libraries\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Laravel\Lumen\Routing\Controller;

abstract class ResourcesController extends Controller
{

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * ResourcesController constructor.
     *
     * @param \App\Libraries\Repository $repository
     */
    public function __construct(\App\Libraries\Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->json($this->repository->all(Input::get('paginate', false), Input::get('nbByPage', 15), Input::get('page', 1)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        try {
            $model = $this->repository->create(Input::all());
        } catch (ModelNotValid $e) {
            return response()->json($e->getErrors(), 400);
        }

        return response()->json($model, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

        $model = $this->repository->find($id);

        if(is_null($model)) {
            return response()->json([], 404);
        }

        if(!$this->isAllowModel($model)) {
            return response()->json([], 403);
        }

        return response()->json($model, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $model = $this->repository->find($id);

        if(is_null($model)) {
            return response()->json([], 404);
        }

        if(!$this->isAllowModel($model)) {
            return response()->json([], 403);
        }

        try {
            $model = $this->repository->update($model, Input::all());
        } catch (ModelNotValid $e) {
            return response()->json($e->getErrors(), 400);
        }

        return response()->json($model, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $model = $this->repository->find($id);

        if(is_null($model)) {
            return response()->json([], 404);
        }

        if(!$this->isAllowModel($model)) {
            return response()->json([], 403);
        }

        $this->repository->delete($model);

        return response()->json([], 204);
    }

    /**
     * Check if the current user can edit this model
     * @param $model
     *
     * @return bool
     */
    protected function isAllowModel($model)
    {
        if(!$model instanceof UserRestrictionInterface) {
            return true;
        }

        return \Acl::isUserAllowModel($model, \Auth::user());
    }
}