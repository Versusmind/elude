<?php namespace Tests\Unit;

use App\Libraries\Acl\Manager\Manager;
use Tests\TestCase;

/**
 * @group unit
 **/
abstract class ManagerTest extends TestCase
{
    /**
     * @var Manager
     */
    protected $repository;

    /**
     * @return Manager
     */
    public abstract function getManager();

    public function setUp()
    {
        $this->repository = $this->getManager();

        parent::setUp();

        $this->initiate();
    }

    public abstract function testIsAllowOk();
    public abstract function testIsAllowKo();

    public abstract function testGrantOk();
    public abstract function testGrantKo();

    public abstract function testDenyOk();
    public abstract function testDenyKo();

    public abstract function testAll();

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
}