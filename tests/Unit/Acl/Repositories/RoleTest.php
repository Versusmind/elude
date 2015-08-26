<?php namespace Tests\Unit\Acl\Repositories;

use App\Libraries\Acl\Repositories\Role;
use App\Libraries\Repository;
use Tests\Unit\RepositoryTest;

/**
 * @group unit
 **/
class RoleTest extends RepositoryTest
{

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return new Role();
    }

    public function createOkProvider()
    {
        return [
            [
                [
                    'name' => 'test',
                    'filter' => 'A'
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
                1, ['name' => 'update']
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
                    'filter' => 'A'
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
                1, ['name' => null]
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
                    'name' => null,
                    'filter' => 'A'
                ],
                [
                    'name' => 'test',
                    'filter' => 'DUMMY'
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