<?php namespace Tests\Unit\Acl\Repositories;

use App\Libraries\Acl\Repositories\Permission;
use App\Libraries\Repository;
use Tests\Unit\RepositoryTest;

/**
 * @group unit
 **/
class PermissionTest extends RepositoryTest
{

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return new Permission();
    }

    public function createOkProvider()
    {
        return [
            [
                [
                    'area'        => 'macdonald',
                    'permission'  => 'bigmac',
                    'action'      => 'order',
                    'description' => 'Order bigmac'
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function findKoProvider()
    {
        return [
            [-1]
        ];
    }

    /**
     * @return array
     */
    public function updateOkProvider()
    {
        return [
            [
                1, ['action' => 'update']
            ]
        ];
    }

    /**
     * @return array
     */
    public function whereOkProvider()
    {
        return [
            [
                [
                    'area' => 'macdonald'
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function deleteKoProvider()
    {
        return [
            [-1]
        ];
    }

    /**
     * @return array
     */
    public function updateKoProvider()
    {
        return [
            [
                1, [uniqid() => 'DUMMY']
            ]
        ];
    }

    /**
     * @return array
     */
    public function findOkProvider()
    {
        return [
            [1]
        ];
    }

    /**
     * @return array
     */
    public function createKoProvider()
    {
        return [
            [
                [
                    'area'        => null,
                    'permission'  => 'bigmac',
                    'action'      => 'order',
                    'description' => 'Order bigmac'
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function deleteOkProvider()
    {
        return [
            [1]
        ];
    }
}