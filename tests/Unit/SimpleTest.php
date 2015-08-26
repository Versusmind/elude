<?php namespace Tests\Unit;


use Tests\TestCase;

class SimpleTest extends TestCase
{

    /**
     * GroupTest constructor.
     */
    public function __construct()
    {
        parent::__construct(new \App\Libraries\Acl\Repositories\Group());
    }

    /**
     * @dataProvider additionProvider
     */
    public function testAdd($a, $b, $expected)
    {
        $this->assertEquals($expected, $a + $b);
    }

    public function additionProvider()
    {
        return array(
            array(0, 0, 0),
            array(0, 1, 1),
            array(1, 0, 1),
            array(1, 1, 3)
        );
    }
}