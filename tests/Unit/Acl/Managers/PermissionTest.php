<?php namespace Tests\Unit\Acl\Managers;

use App\Libraries\Acl\Manager\Manager;
use App\Libraries\Acl\Manager\Permission;
use App\Libraries\Acl\PermissionResolver;
use Tests\Unit\ManagerTest;

/**
 * @group unit
 **/
class PermissionTest extends ManagerTest
{

    /**
     * @return Manager
     */
    public function getManager()
    {
        return new Permission(new PermissionResolver(), new \App\Libraries\Acl\Repositories\Permission());
    }

    public function testIsAllowOk()
    {
        $this->assertTrue(false);
    }

    public function testIsAllowKo()
    {
        $this->assertTrue(false);
    }

    public function testGrantOk()
    {
        $this->assertTrue(false);
    }

    public function testGrantKo()
    {
        $this->assertTrue(false);
    }

    public function testDenyOk()
    {
        $this->assertTrue(false);
    }

    public function testDenyKo()
    {
        $this->assertTrue(false);
    }

    public function testAll()
    {
        $this->assertTrue(false);
    }
}