<?php namespace Tests\Unit\Acl\Managers;

use App\Libraries\Acl\Manager\Manager;
use App\Libraries\Acl\Manager\User;
use App\Libraries\Acl\PermissionResolver;
use Tests\Unit\Acl\ManagerTest;

/**
 * @group unit
 **/
class UserTest extends ManagerTest
{
    /**
     * @return Manager
     */
    public function getManager()
    {
        return new User(new PermissionResolver(), new \App\Libraries\Acl\Repositories\User());
    }

    public function testIsAllowOk()
    {
        $this->groupRepository->addPermission($this->user, $this->permissionFirst);
        $this->assertTrue($this->manager->isAllow($this->user, $this->permissionFirst->getAction()));
        $this->assertFalse($this->manager->isAllow($this->user, $this->permissionSecond->getAction()));
    }

    public function testGrantOk()
    {
        $this->assertFalse($this->manager->isAllow($this->user, $this->permissionSecond->getAction()));
        $this->assertTrue($this->user->cannot($this->permissionSecond->getAction()));
        $this->manager->grant($this->user, $this->permissionSecond);
        $this->assertTrue($this->manager->isAllow($this->user, $this->permissionSecond->getAction()));
        $this->assertTrue($this->user->can($this->permissionSecond->getAction()));
    }

    public function testGrantKo()
    {
        $this->assertFalse($this->manager->isAllow($this->user, $this->permissionSecond->getAction()));
        $this->manager->grant($this->user, $this->permissionSecond);
        $this->manager->grant($this->user, $this->permissionSecond); // double add
        $this->assertTrue($this->manager->isAllow($this->user, $this->permissionSecond->getAction()));
    }

    public function testDenyOk()
    {
        $this->manager->grant($this->user, $this->permissionSecond);
        $this->assertTrue($this->manager->isAllow($this->user, $this->permissionSecond->getAction()));
        $this->manager->deny($this->user, $this->permissionSecond);
        $this->assertFalse($this->manager->isAllow($this->user, $this->permissionSecond->getAction()));
    }

    public function testAll()
    {
        $this->manager->grant($this->user, $this->permissionSecond);
        $permissions = $this->manager->getAllPermissions($this->user);
        $this->assertEquals(1, count($permissions));
        $this->assertContains($this->permissionSecond->getAction(), $permissions);
    }
}