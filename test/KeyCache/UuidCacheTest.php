<?php

namespace Islandora\Chullo\KeyCache;

use Islandora\Chullo\Uuid\UuidGenerator;
use Islandora\Chullo\KeyCache\RedisKeyCache;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class UuidCacheTest extends \PHPUnit_Framework_TestCase
{

    protected $redis;
    
    protected $uuid_gen;
    
    public function setUp()
    {
        $this->redis = \Mockery::mock('\Redis');
        $this->redis->shouldReceive('expire')->andReturn(1);
        $this->redis->shouldReceive('ping')->andReturn("+PONG");
        $this->redis->shouldReceive('close')->andReturn(null);

        $this->uuid_gen = new UuidGenerator();
    }
    
    /**
     * @covers RedisKeyCache::set
     * @group UnitTest
     */
    public function testAddUuidPair()
    {
        // Need both to account for OS X and Linux differences.
        $this->redis->shouldReceive('hSetNx')->andReturn(1);
        $this->redis->shouldReceive('hsetnx')->andReturn(1);
        $redis_cache = new RedisKeyCache($this->redis, 'localhost', 6379);

        $transId = "tx:" . $this->uuid_gen->generateV4();
        $uuid = $this->uuid_gen->generateV4();
        $path = "http://localhost:8080/fcrepo/rest/object1";

        $this->assertEquals(1, $redis_cache->set($transId, $uuid, $path), "Error setting uuid->path hash");
    }

    /**
     * @covers RedisKeyCache::getByUuid
     * @group UnitTest
     */
    public function testGetByUuid()
    {
        $txID = $transId = "tx:" . $this->uuid_gen->generateV4();
        $uuid = $this->uuid_gen->generateV4();
        $path = "http://localhost:8080/fcrepo/rest/object1";
        
        // Need both to account for OS X and Linux differences.
        $this->redis->shouldReceive('hGet')->with($txID, $uuid)->andReturn($path);
        $this->redis->shouldReceive('hget')->with($txID, $uuid)->andReturn($path);
        
        $redis_cache = new RedisKeyCache($this->redis, 'localhost', 6379);
        
        $this->assertEquals($path, $redis_cache->getByUuid($txID, $uuid), "Error getting by Uuid");
    }

    /**
     * @covers RedisKeyCache::getByPath
     * @group UnitTest
     */
    public function testGetByPath()
    {
        $txID = $transId = "tx:" . $this->uuid_gen->generateV4();

        $uuid1 = $this->uuid_gen->generateV4();
        $path1 = "http://localhost:8080/fcrepo/rest/object1";
        
        $uuid2 = $this->uuid_gen->generateV4();
        $path2 = "http://localhost:8080/fcrepo/rest/object2";
        
        $hashes = array(
            $uuid1 => $path1,
            $uuid2 => $path2,
        );
        
        // Need both to account for OS X and Linux differences.
        $this->redis->shouldReceive('hGetAll')->with($txID)->andReturn($hashes);
        $this->redis->shouldReceive('hgetall')->with($txID)->andReturn($hashes);
        
        $redis_cache = new RedisKeyCache($this->redis, 'localhost', 6379);
        
        $this->assertEquals($uuid2, $redis_cache->getByPath($txID, $path2), "Error getting by Path");
    }

    /**
     * @covers RedisKeyCache::delete
     * @group UnitTest
     */
    public function testDelete()
    {
        $txID = $transId = "tx:" . $this->uuid_gen->generateV4();
        
        $this->redis->shouldReceive('del')->with($txID)->andReturn(1);
        
        $redis_cache = new RedisKeyCache($this->redis, 'localhost', 6379);
        
        $this->assertEquals(1, $redis_cache->delete($txID), "Error deleting transaction ID.");
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
