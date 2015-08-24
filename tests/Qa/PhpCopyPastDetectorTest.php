<?php

/**
 * Class PhpCopyPastDetectorTest
 *
 *
 *
 * @author LAHAXE Arnaud
 * @group quality
 */
class PhpCopyPastDetectorTest extends TestCase
{
    public function testCodeQuality()
    {
        $status = $this->artisan('qa:phpcpd');

        $this->assertEquals(0, $status);
    }
}