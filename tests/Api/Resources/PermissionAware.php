<?php namespace Tests\Api\Resources;

use Tests\Api\ResourcesControllerTest;

abstract class PermissionAware extends ResourcesControllerTest
{

    /**
     * @var \App\Permission
     */
    protected $permission;


    public function setUp()
    {
        parent::setUp();

        $this->permission = (new \App\Libraries\Acl\Repositories\Permission())->create(new \App\Permission([
            'area'        => uniqid(),
            'permission'  => uniqid(),
            'description' => 'test'
        ]));
    }

    public function addPermission($modelId, $permissionId)
    {
        $this->call('POST', $this->apiPath . $this->resourceName . '/' . $modelId . '/permissions/' . $permissionId);
        $this->seeJson([]);
        $this->seeStatusCode(202);
        $model = json_decode($this->response->getContent());
        $this->assertTrue(is_array($model->permissions));
        $this->assertGreaterThanOrEqual(1, count($model->permissions));
        $isPermissionInArray = false;
        foreach($model->permissions as $permission) {
            if($permission->id == $permissionId) {
                $isPermissionInArray = true;
                break;
            }
        }

        $this->assertTrue($isPermissionInArray, 'Permission is not in array');
    }

    public function removePermission($modelId, $permissionId)
    {
        $this->call('DELETE', $this->apiPath . $this->resourceName . '/' . $modelId . '/permissions/' . $permissionId);
        $this->seeJson([]);
        $this->seeStatusCode(202);
        $model = json_decode($this->response->getContent());
        $this->assertTrue(is_array($model->permissions));
        $isPermissionInArray = false;
        foreach($model->permissions as $permission) {
            if($permission->id == $permissionId) {
                $isPermissionInArray = true;
                break;
            }
        }

        $this->assertFalse($isPermissionInArray, 'Permission is in array');
    }


    public abstract function testAddPermission();
    public abstract function testRemovePermission();
}