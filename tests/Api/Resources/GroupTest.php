<?php namespace Tests\Api\Resources;
use App\Libraries\Acl\Repositories\Group;

/**
 * @group api
 */
class GroupTest extends RoleAware
{
    function getResourceName()
    {
        return 'groups';
    }

    function createOkProvider()
    {
        return [
            [
                ['name' => uniqid()],
                ['id' => self::NUMBER, 'name' => self::STRING]
            ]
        ];
    }

    function createKoProvider()
    {
        return [
            [
                ['name' => null]
            ]
        ];
    }

    function updateOkProvider()
    {
        return [
            [1, ['name' => uniqid()], ['id' => self::NUMBER, 'name' => self::STRING]]
        ];
    }

    function updateKoProvider()
    {
        return [
            [1, ['name' => null], 400]
        ];
    }

    function findOkProvider()
    {
        return [
            [1, ['id' => self::NUMBER, 'name' => self::STRING]]
        ];
    }

    public function testAddPermission()
    {
        $group = (new Group())->create([
            'name' => uniqid()
        ]);

        $this->addPermission($group->id, $this->permission->id);
    }

    public function testRemovePermission()
    {
        $group = (new Group())->create([
            'name' => uniqid()
        ]);
        $this->addPermission($group->id, $this->permission->id);
        $this->removePermission($group->id, $this->permission->id);
    }

    public function testAddRole()
    {
        $group = (new Group())->create([
            'name' => uniqid()
        ]);

        $this->addRole($group->id, $this->role->id);
    }

    public function testRemoveRole()
    {
        $group = (new Group())->create([
            'name' => uniqid()
        ]);

        $this->addRole($group->id, $this->role->id);
        $this->removeRole($group->id, $this->role->id);

    }
}