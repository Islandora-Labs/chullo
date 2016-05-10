<?php
/**
 * @file
 * This is part of Chullo service.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Islandora\Chullo\KeyCache;

/**
 * Implementation of the IKeyCache using Redis and phpredis.
 *
 * @author Jared Whiklo <jwhiklo@gmail.com>
 * @since 2016-04-12
 */
class RedisKeyCache implements IUuidCache
{
  
    /**
     * @var Redis $redis
     *   The Redis object.
     */
    protected $redis;
    /**
     * @var bool $connected
     *   Are we connected to the Redis server yet.
     */
    private $connected = false;
  
    /**
     * @param \Redis $instance
     *   An instance of the Redis client
     */
    public function __construct(\Redis $instance)
    {
        $this->redis = $instance;
    }
  
    /**
     * Check if we are connected to the Redis server.
     *
     * @return bool Whether we are connected.
     */
    private function isConnected()
    {
        try {
            $result = $this->redis->ping();
            $connected = ($result == "PONG");
        } catch (RedisException $e) {
            error_log("RedisKeyCache: Error communicating with Redis server - " . $e->getMessage());
        }
        return $connected;
    }

    /**
     * {@inheritdoc}
     */
    public function set($txID, $uuid, $path, $expire = 3600)
    {
        if ($this->isConnected()) {
            $result = $this->redis->hSetNx($txID, $uuid, $path);
            if ($result) {
                $this->redis->expire($txID, $expire);
            }
            return $result;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getByUuid($txId, $uuid)
    {
        if ($this->isConnected()) {
            return $this->redis->hget($txId, $uuid);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getByPath($txId, $path)
    {
        if ($this->isConnected()) {
            $hashes = $this->redis->hgetall($txId);
            if ($hashes !== false) {
                $flipped = array_flip($hashes);
                if (array_key_exists($path, $flipped)) {
                    return $flipped[$path];
                }
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function extend($txId, $expire = 3600)
    {
        if ($this->isConnected()) {
            return $this->redis->expire($txId, $expire);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($txId)
    {
        if ($this->isConnected()) {
            return $this->redis->del($txId);
        }
    }
}
