<?php namespace Tests\Api;

use Tests\TestCase;

/**
 * @group api
 */
abstract class ResourcesControllerTest extends TestCase
{

    protected $resourceName;

    protected $apiPath = '/api/v1/';

    public function setUp()
    {
        $this->resourceName = $this->getResourceName();

        parent::setUp();
    }

    /**
     * @return array
     */
    abstract function getResourceName();

    /**
     * @return array
     */
    abstract function createOkProvider();

    /**
     * @return array
     */
    abstract function createKoProvider();

    /**
     * @return array
     */
    abstract function updateOkProvider();

    /**
     * @return array
     */
    abstract function updateKoProvider();

    /**
     * @return array
     */
    abstract function findOkProvider();

    /**
     * @return array
     */
    abstract function findKoProvider();

    /**
     * @return array
     */
    abstract function deleteOkProvider();

    /**
     * @return array
     */
    abstract function deleteKoProvider();

    /**
     * @param $attributes
     *
     * @dataProvider createOkProvider
     */
    public function testCreateOk($attributes, $pattern)
    {

        $this->assertTrue(false);
    }

    /**
     * @param $attributes
     *
     * @dataProvider createKoProvider
     *
     */
    public function testCreateKo($attributes)
    {

        $this->assertTrue(false);
    }

    /**
     * @param $id
     *
     * @dataProvider findOkProvider
     * @depends      testCreateOk
     */
    public function testFindOk($id, $pattern)
    {
        $this->call('GET', $this->apiPath . $this->resourceName . '/' . $id);
        $this->seeJson([]);
        $this->seeStatusCode(200);
        $this->assertMatchPattern($pattern);
    }

    /**
     * @param $id
     *
     * @dataProvider findKoProvider
     */
    public function testFindKo($id)
    {
        $this->call('GET', $this->apiPath . $this->resourceName . '/' . $id);
        $this->seeJson([]);
        $this->seeStatusCode(404);
    }

    /**
     * @param $id
     * @param $data
     *
     * @dataProvider updateOkProvider
     * @depends      testFindOk
     */
    public function testUpdateOk($id, $data, $pattern)
    {
        $this->call('PUT', $this->apiPath . $this->resourceName . '/' . $id, $data);
        $this->seeJson([]);
        $this->seeStatusCode(204);
        $this->assertMatchPattern($pattern);
    }

    /**
     * @param $id
     * @param $data
     *
     * @dataProvider updateKoProvider
     */
    public function testUpdateKo($id, $data, $status)
    {
        $this->call('PUT', $this->apiPath . $this->resourceName . '/' . $id, $data);
        $this->seeJson([]);
        $this->seeStatusCode($status);
    }

    /**
     * @param $id
     *
     * @dataProvider deleteOkProvider
     * @depends      testUpdateOk
     */
    public function testDeleteOk($id)
    {
        $this->call('DELETE', $this->apiPath . $this->resourceName . '/' . $id);
        $this->seeJson([]);
        $this->seeStatusCode(204);
    }

    public function testAllOk()
    {
        $this->call('GET', $this->apiPath . $this->resourceName);
        $this->seeJson([]);
        $this->seeStatusCode(200);
    }

    public function testAllPaginateOk()
    {
        $this->call('GET', $this->apiPath . $this->resourceName, [
            'paginate' => 1
        ]);
        $this->seeJson([]);
        $this->seeStatusCode(200);
    }

    public function testAllKo()
    {
        $this->assertTrue(false);
    }
}