<?php namespace Tests\Api\Resources;

use Tests\Api\ResourcesControllerTest;

/**
 * @group api
 */
class RoleTest extends ResourcesControllerTest
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
}