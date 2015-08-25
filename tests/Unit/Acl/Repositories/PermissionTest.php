<?php namespace Tests\Unit\Acl\Repositories;

use App\Libraries\Acl\Repositories\Permission;
use Tests\Unit\RepositoryTest;

/**
 * Date: 25/08/2015
 * Time: 14:15
 * FileName : GroupTest.php
 * Project : myo2
 */
class PermissionTest extends RepositoryTest
{

    /**
     * PermissionTest constructor.
     */
    public function __construct()
    {
        parent::__construct(new Permission());
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

    /**
     * @return array
     */
    public function createOkProvider()
    {
        // TODO: Implement createOkProvider() method.
    }

    /**
     * @return array
     */
    public function findKoProvider()
    {
        // TODO: Implement findKoProvider() method.
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
}