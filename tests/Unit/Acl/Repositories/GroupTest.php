<?php namespace Tests\Unit\Acl\Repositories;

use App\Libraries\Acl\Repositories\Group;
use App\Libraries\Repository;
use Tests\Unit\RepositoryTest;

/**
 * @group unit
 **/
class GroupTest extends RepositoryTest
{

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return new Group();
    }

    public function createOkProvider()
    {
        return [
            [
                ['name' => 'test']
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
                    'name' => 'test'
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
                1, [uniqid() => 'update']
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
                    'name' => null
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