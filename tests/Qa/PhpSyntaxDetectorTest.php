<?php namespace Tests\Qa;

use Tests\TestCase;

/**
 * Class PhpSyntaxDetectorTest
 *
 *
 *
 * @author LAHAXE Arnaud
 * @group quality
 */
class PhpSyntaxDetectorTest extends TestCase
{

    public function testCodeQuality()
    {
        $status = $this->artisan('qa:phpcs');

        $this->assertEquals(0, $status);
    }
}