<?php namespace Tests\Unit;

use App\Libraries\Acl\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Tests\TestCase;

/**
 * Date: 25/08/2015
 * Time: 14:13
 * FileName : Repositories.php
 * Project : myo2
 */
abstract class RepositoryTest extends TestCase
{

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * RepositoryTest constructor.
     *
     * @param \App\Libraries\Acl\Repositories\Repository $repository
     */
    public function __construct(\App\Libraries\Acl\Repositories\Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public abstract function findOkProvider();

    /**
     * @return array
     */
    public abstract function createKoProvider();

    /**
     * @return array
     */
    public abstract function createOkProvider();

    /**
     * @return array
     */
    public abstract function findKoProvider();

    /**
     * @return array
     */
    public abstract function updateOkProvider();

    /**
     * @return array
     */
    public abstract function whereOkProvider();

    /**
     * @return array
     */
    public abstract function deleteKoProvider();

    /**
     * @return array
     */
    public abstract function updateKoProvider();

    protected function assertModel($actual, $saved = true)
    {
        $this->assertInstanceOf($this->repository->getModelClass(), $actual);

        if ($saved) {
            /** @var Model $actual */
            $this->assertFalse($actual->isDirty());
        }
    }

    /**
     * @param $attributes
     *
     * @dataProvider createOkProvider
     */
    public function testCreateOk($attributes)
    {
        $model = $this->repository->create($attributes);

        $this->assertModel($model);
    }

    /**
     * @param $attributes
     *
     * @dataProvider createKoProvider
     */
    public function testCreateKo($attributes)
    {
        $model = $this->repository->create($attributes);

        $this->assertModel($model, false);
    }

    /**
     * @param $id
     *
     * @dataProvider findOkProvider
     */
    public function testFindOk($id)
    {
        $model = $this->repository->find($id);

        $this->assertModel($model);
    }

    /**
     * @param $id
     *
     * @dataProvider findKoProvider
     */
    public function testFindKo($id)
    {
        $model = $this->repository->find($id);

        $this->assertNull($model);
    }

    /**
     * @param $id
     * @param $data
     *
     * @dataProvider updateOkProvider
     */
    public function testUpdateOk($id, $data)
    {
        $model = $this->repository->update($this->repository->find($id), $data);

        $this->assertModel($model);
    }

    /**
     * @param $id
     * @param $data
     *
     * @dataProvider updateKoProvider
     */
    public function testUpdateKo($id, $data)
    {
        $model = $this->repository->update($this->repository->find($id), $data);

        $this->assertModel($model, false);
    }

    /**
     * @param $id
     *
     * @dataProvider deleteOkProvider
     */
    public function testDeleteOk($id)
    {
        $result = $this->repository->delete($this->repository->find($id));
        $this->assertTrue($result);

        $model = $this->repository->find($id);

        $this->assertNull($model);
    }

    public function testAllOk()
    {
        $this->assertInstanceOf(Collection::class, $this->repository->all());
    }

    public function testAllPaginateOk()
    {
        $this->assertInstanceOf(Paginator::class, $this->repository->all(true));
    }

    public function testAllKo()
    {
        $this->assertInstanceOf(Paginator::class, $this->repository->all(true, -1));
    }

    /**
     * @param $where
     *
     * @dataProvider whereOkProvider
     */
    public function testWhereOk($where)
    {
        $this->assertInstanceOf(Collection::class, $this->repository->where($where));
    }

    /**
     * @param $where
     *
     * @dataProvider whereOkProvider
     */
    public function testWherePaginateOk($where)
    {
        $this->assertInstanceOf(Paginator::class, $this->repository->where($where, true));
    }

    public function testWhereKo()
    {
        $this->repository->where([
            uniqid() => uniqid()
        ]);
    }
}