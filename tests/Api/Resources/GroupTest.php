<?php namespace Tests\Api\Resources;

use Tests\Api\ResourcesControllerTest;

/**
 * @group api
 */
class GroupTest extends ResourcesControllerTest
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
}