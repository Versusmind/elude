<?php namespace Tests\Unit\Acl\Managers;

use App\Libraries\Acl\Manager\Group;
use App\Libraries\Acl\Manager\Manager;
use App\Libraries\Acl\PermissionResolver;
use Tests\Unit\Acl\ManagerTest;

/**
 * @group unit
 **/
class GroupTest extends ManagerTest
{

    /**
     * @return Manager
     */
    public function getManager()
    {
        return new Group(new PermissionResolver(), new \App\Libraries\Acl\Repositories\Group());
    }

    public function testIsAllowOk()
    {
        $this->groupRepository->addPermission($this->group, $this->permissionFirst);
        $this->assertTrue($this->manager->isAllow($this->group, $this->permissionFirst->getAction()));
        $this->assertFalse($this->manager->isAllow($this->group, $this->permissionSecond->getAction()));
    }

    public function testGrantOk()
    {
        $this->assertFalse($this->manager->isAllow($this->group, $this->permissionSecond->getAction()));
        $this->manager->grant($this->group, $this->permissionSecond);
        $this->assertTrue($this->manager->isAllow($this->group, $this->permissionSecond->getAction()));
    }

    public function testGrantKo()
    {
        $this->assertFalse($this->manager->isAllow($this->group, $this->permissionSecond->getAction()));
        $this->manager->grant($this->group, $this->permissionSecond);
        $this->manager->grant($this->group, $this->permissionSecond); // double add
        $this->assertTrue($this->manager->isAllow($this->group, $this->permissionSecond->getAction()));
    }

    public function testDenyOk()
    {
        $this->manager->grant($this->group, $this->permissionSecond);
        $this->assertTrue($this->manager->isAllow($this->group, $this->permissionSecond->getAction()));
        $this->manager->deny($this->group, $this->permissionSecond);
        $this->assertFalse($this->manager->isAllow($this->group, $this->permissionSecond->getAction()));
    }

    public function testAll()
    {
        $this->manager->grant($this->group, $this->permissionSecond);
        $permissions = $this->manager->getAllPermissions($this->group);
        $this->assertEquals(1, count($permissions));
        $this->assertContains($this->permissionSecond->getAction(), $permissions);
    }
}