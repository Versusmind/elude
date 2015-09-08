<?php namespace Tests\Api\Resources;

use App\Role;

abstract class RoleAware extends PermissionAware
{
    /**
     * @var \App\Role
     */
    protected $role;


    public function setUp()
    {
        parent::setUp();

        $this->role = (new \App\Libraries\Acl\Repositories\Role())->create(new Role([
            'name' => uniqid(),
            'filter' => 'A',
        ]));
    }

    public function addRole($modelId, $roleId)
    {
        $this->call('POST', $this->apiPath . $this->resourceName . '/' . $modelId . '/roles/' . $roleId);
        $this->seeJson([]);
        $this->seeStatusCode(204);
        $model = json_decode($this->response->getContent());
        $this->assertTrue(is_array($model->roles));
        $this->assertGreaterThanOrEqual(1, count($model->roles));
        $isPermissionInArray = false;
        foreach ($model->roles as $role) {
            if ($role->id == $roleId) {
                $isPermissionInArray = true;
                break;
            }
        }

        $this->assertTrue($isPermissionInArray, 'Permission is not in array');
    }

    public function removeRole($modelId, $roleId)
    {
        $this->call('DELETE', $this->apiPath . $this->resourceName . '/' . $modelId . '/roles/' . $roleId);
        $this->seeJson([]);
        $this->seeStatusCode(204);
        $model = json_decode($this->response->getContent());
        $this->assertTrue(is_array($model->roles));
        $isRoleInArray = false;
        foreach ($model->roles as $role) {
            if ($role->id == $roleId) {
                $isRoleInArray = true;
                break;
            }
        }

        $this->assertFalse($isRoleInArray, 'Permission is in array');
    }

    public abstract function testAddRole();

    public abstract function testRemoveRole();
}