<?php namespace Tests\Qa;

use Tests\TestCase;

/**
 * Class PhpMessDetectorTest
 *
 *
 *
 * @author LAHAXE Arnaud
 * @group quality
 */
class PhpMessDetectorTest extends TestCase
{
    public function testCodeQuality()
    {
        $status = $this->artisan('qa:phpmd');

        $this->assertEquals(0, $status);
    }
}