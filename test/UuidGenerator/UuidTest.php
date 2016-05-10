<?php

namespace Islandora\Chullo\Uuid;

use Ramsey\Uuid\Uuid;
use Islandora\Chullo\Uuid\UuidGenerator;

class UuidTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @covers Islandora\Chullo\Uuid\UuidGenerator::generateV4
     */
    public function testGenerateV4()
    {
        # Not much we can do other than verify we got something
        # that looks like a UUID.
        $version4_regex = "/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i";
        $generator = new UuidGenerator();
        $uuid = $generator->generateV4();
        $this->assertEquals(1, preg_match($version4_regex, $uuid), "Did not build a correct Uuid V4.");
    }

    /**
     * @covers Islandora\Chullo\Uuid\UuidGenerator::generateV5
     */
    public function testGenerateV5()
    {
        $namespace = 'islandora.ca';
        $object_name = 'test_object';
        
        $namespace_uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, $namespace);
        $test_uuid = Uuid::uuid5($namespace_uuid->toString(), $object_name);
        
        $generator = new UuidGenerator($namespace);
        $uuid5 = $generator->generateV5($object_name);
        $this->assertEquals($test_uuid->toString(), $uuid5, "Did not build the correct UUID v5.");
        
        $generator2 = new UuidGenerator();
        $uuid5_2 = $generator2->generateV5($object_name, $namespace);
        $this->assertEquals($test_uuid->toString(), $uuid5_2, "Did not build the correct UUID v5");
    }
}
