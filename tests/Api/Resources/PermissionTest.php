<?php namespace Tests\Api\Resources;

use Tests\Api\ResourcesControllerTest;

/**
 * @group api
 */
class PermissionTest extends ResourcesControllerTest
{
    function getResourceName()
    {
        return 'permissions';
    }

    function createOkProvider()
    {
        return [
            [
                ['area' => uniqid(), 'permission' => uniqid(), 'description' =>uniqid()],
                ['id' => self::NUMBER, 'permission' => self::STRING, 'description' => self::STRING, 'area' => self::STRING]
            ]
        ];
    }

    function createKoProvider()
    {
        return [
            [
                ['area' => null],
                ['area' => uniqid()],
            ]
        ];
    }

    function updateOkProvider()
    {
        return [
            [1, ['area' => uniqid()], ['id' => self::NUMBER, 'permission' => self::STRING, 'description' => self::STRING, 'area' => self::STRING]]
        ];
    }

    function updateKoProvider()
    {
        return [
            [1, ['area' => null], 400]
        ];
    }

    function findOkProvider()
    {
        return [
            [1, ['id' => self::NUMBER, 'permission' => self::STRING, 'description' => self::STRING, 'area' => self::STRING]]
        ];
    }
}