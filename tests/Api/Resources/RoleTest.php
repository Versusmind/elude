<?php namespace Tests\Api\Resources;

use App\Libraries\Acl\Repositories\Role;

/**
 * @group api
 */
class RoleTest extends PermissionAware
{
    function getResourceName()
    {
        return 'roles';
    }

    function createOkProvider()
    {
        return [
            [
                ['name' => uniqid(), 'filter' => 'A'],
                ['id' => self::NUMBER, 'name' => self::STRING, 'filter' => self::STRING]
            ]
        ];
    }

    function createKoProvider()
    {
        return [
            [
                ['name' => null],
                ['name' => uniqid(), 'filter' => 'DUMMY'],
            ]
        ];
    }

    function updateOkProvider()
    {
        return [
            [1, ['name' => uniqid()], ['id' => self::NUMBER, 'name' => self::STRING, 'filter' => self::STRING]]
        ];
    }

    function updateKoProvider()
    {
        return [
            [1, ['name' => null], 400],
            [1, ['filter' => 'DUMMY'], 400],
        ];
    }

    function findOkProvider()
    {
        return [
            [1, ['id' => self::NUMBER, 'name' => self::STRING, 'filter' => self::STRING]]
        ];
    }

    public function testAddPermission()
    {
        $role = (new Role())->create(new \App\Role([
            'name' => uniqid(),
            'filter' => 'A'
        ]));

        $this->addPermission($role->id, $this->permission->id);
    }

    public function testRemovePermission()
    {
        $role = (new Role())->create(new \App\Role([
            'name' => uniqid(),
            'filter' => 'A'
        ]));

        $this->addPermission($role->id, $this->permission->id);
        $this->removePermission($role->id, $this->permission->id);
    }
}