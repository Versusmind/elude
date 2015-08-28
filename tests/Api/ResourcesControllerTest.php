<?php namespace Tests\Api;

use Tests\TestCase;

/**
 * @group api
 */
abstract class ResourcesControllerTest  extends TestCase
{
    protected $resourceName;

    public function setUp()
    {
        $this->resourceName = $this->getResourceName();

        parent::setUp();
    }

    abstract function getResourceName();

    abstract function createOkProvider();
    abstract function createKoProvider();

    abstract function updateOkProvider();
    abstract function updateKoProvider();

    abstract function findOkProvider();
    abstract function findKoProvider();

    abstract function deleteOkProvider();
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
     * @depends testCreateOk
     */
    public function testFindOk($id, $pattern)
    {

        $this->assertTrue(false);
    }

    /**
     * @param $id
     *
     * @dataProvider findKoProvider
     */
    public function testFindKo($id)
    {

        $this->assertTrue(false);
    }

    /**
     * @param $id
     * @param $data
     *
     * @dataProvider updateOkProvider
     * @depends testFindOk
     */
    public function testUpdateOk($id, $data, $pattern)
    {

        $this->assertTrue(false);
    }

    /**
     * @param $id
     * @param $data
     *
     * @dataProvider updateKoProvider
     */
    public function testUpdateKo($id, $data)
    {

        $this->assertTrue(false);
    }

    /**
     * @param $id
     *
     * @dataProvider deleteOkProvider
     * @depends testUpdateOk
     */
    public function testDeleteOk($id)
    {

        $this->assertTrue(false);
    }

    public function testAllOk()
    {
        $this->assertTrue(false);
    }

    public function testAllPaginateOk()
    {
        $this->assertTrue(false);
    }

    public function testAllKo()
    {
        $this->assertTrue(false);
    }
}