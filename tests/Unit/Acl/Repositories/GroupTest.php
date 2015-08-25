<?php namespace Tests\Unit\Acl\Repositories;

use App\Libraries\Acl\Repositories\Group;
use App\Libraries\Repository;
use Tests\Unit\RepositoryTest;

/**
 * Class GroupTest
 * @package Tests\Unit\Acl\Repositories
 */
class GroupTest extends RepositoryTest
{



    public function createOkProvider()
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
        // TODO: Implement updateOkProvider() method.
    }

    /**
     * @return array
     */
    public function whereOkProvider()
    {
        // TODO: Implement whereOkProvider() method.
    }

    /**
     * @return array
     */
    public function deleteKoProvider()
    {
        // TODO: Implement deleteKoProvider() method.
    }

    /**
     * @return array
     */
    public function updateKoProvider()
    {
        // TODO: Implement updateKoProvider() method.
    }

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return new Group();
    }

    /**
     * @return array
     */
    public function findOkProvider()
    {
        // TODO: Implement findOkProvider() method.
    }

    /**
     * @return array
     */
    public function createKoProvider()
    {
        // TODO: Implement createKoProvider() method.
    }
}