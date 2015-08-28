<?php namespace Tests\Unit\Acl\Managers;

use App\Libraries\Acl\Manager\Manager;
use App\Libraries\Acl\Manager\Role;
use App\Libraries\Acl\PermissionResolver;
use Tests\Unit\Acl\ManagerTest;

/**
 * @group unit
 **/
class RoleTest extends ManagerTest
{

    /**
     * @return Manager
     */
    public function getManager()
    {
        return new Role(new PermissionResolver(), new \App\Libraries\Acl\Repositories\Role());
    }

    public function testIsAllowOk()
    {
        $this->groupRepository->addPermission($this->role, $this->permissionFirst);
        $this->assertTrue($this->manager->isAllow($this->role, $this->permissionFirst->getAction()));
        $this->assertFalse($this->manager->isAllow($this->role, $this->permissionSecond->getAction()));
    }

    public function testGrantOk()
    {
        $this->assertFalse($this->manager->isAllow($this->role, $this->permissionSecond->getAction()));
        $this->manager->grant($this->role, $this->permissionSecond);
        $this->assertTrue($this->manager->isAllow($this->role, $this->permissionSecond->getAction()));
    }

    public function testGrantKo()
    {
        $this->assertFalse($this->manager->isAllow($this->role, $this->permissionSecond->getAction()));
        $this->manager->grant($this->role, $this->permissionSecond);
        $this->manager->grant($this->role, $this->permissionSecond); // double add
        $this->assertTrue($this->manager->isAllow($this->role, $this->permissionSecond->getAction()));
    }

    public function testDenyOk()
    {
        $this->manager->grant($this->role, $this->permissionSecond);
        $this->assertTrue($this->manager->isAllow($this->role, $this->permissionSecond->getAction()));
        $this->manager->deny($this->role, $this->permissionSecond);
        $this->assertFalse($this->manager->isAllow($this->role, $this->permissionSecond->getAction()));
    }

    public function testAll()
    {
        $this->manager->grant($this->role, $this->permissionSecond);
        $permissions = $this->manager->getAllPermissions($this->role);
        $this->assertEquals(1, count($permissions));
        $this->assertContains($this->permissionSecond->getAction(), $permissions);
    }
}