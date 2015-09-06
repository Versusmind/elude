<?php namespace Tests\Unit;

use App\Libraries\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

/**
 * @group unit
 **/
abstract class RepositoryTest extends TestCase
{

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @return Repository
     */
    public abstract function getRepository();

    public function setUp()
    {
        $this->repository = $this->getRepository();

        parent::setUp();

        $this->initiate();
    }

    /**
     * You can use this method to seed the db
     * after database refresh
     *
     * @return bool
     */
    public function initiate()
    {
        return true;
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
    public abstract function deleteOkProvider();

    /**
     * @return array
     */
    public abstract function updateKoProvider();

    protected function assertModel($actual, $saved = true)
    {
        $this->assertInstanceOf($this->repository->getModelClass(), $actual);

        if ($saved) {
            /** @var Model $actual */
            $this->assertTrue($actual->exists);
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
     * @expectedException \App\Libraries\Acl\Exceptions\ModelNotValid
     *
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
     * @depends testCreateOk
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
     * @depends testFindOk
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
     * @expectedException \App\Libraries\Acl\Exceptions\ModelNotValid
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
     * @depends testUpdateOk
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
        $this->assertInstanceOf(LengthAwarePaginator::class, $this->repository->all(true));
    }

    public function testAllKo()
    {
        $this->assertInstanceOf(LengthAwarePaginator::class, $this->repository->all(true, -1));
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
        $this->assertInstanceOf(LengthAwarePaginator::class, $this->repository->where($where, true));
    }
}