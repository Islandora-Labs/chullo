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
class RedisKeyCache implements IKeyCache {
  
  /**
   * @var string $server_uri
   *   the hostname of the Redis server.
   */
  protected $server_uri;
  /**
   * @var int $server_port
   *   the port of the Redis server.
   */  
  protected $server_port;
  /**
   * @var int $timeout
   *   timeout value for communication.
   */
  protected $timeout;
  /**
   * @var Redis $redis
   *   The Redis object.
   */
  protected $redis;
  /**
   * @var bool $connected
   *   Are we connected to the Redis server yet.
   */
  private $connected = False;
  
  /**
   * @param string $host
   *   The hostname of the redis server.
   * @param int $port
   *   The port of the redis server.
   * @param int $timeout
   *   The timeout value (in seconds), 0 for unlimited.
   */
  public function __construct($host, $port, $timeout = 0) {
    $this->server_uri = $host;
    $this->server_port = $port;
    $this->timeout = $timeout;
    $this->redis = new Redis();
   
  }
  
  /**
   * Check if we are connected to the Redis server.
   *
   * @return bool Whether we are connected.
   */
  private function isConnected() {
    $connected = False;
    if ($this->connected) {
      try {
        $result = $this->redis->ping();
        $connected = ($result == "+PONG");
      } catch (RedisException $e) {
        error_log("RedisKeyCache: Error communicating with Redis server - " . $e->getMessage());
      }
    }
    if ($connected === False) {
      $connected = $this->redis->connect($this->server_uri, $this->server_port, $this->timeout);
    }
    return $connected;
  }

  /**
   * {@inheritdoc}
   */
  public function setKey($key, $value, $expire = 3600) {
    if ($this->isConnected()) {
      return $this->redis->setEx($key, $expire, $value);
    }
    return False;
  }

  /**
   * {@inheritdoc}
   */
  public function getKey($key) {
    if ($this->isConnected()) {
      return $this->redis->get($key);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function extend($key, $expire) {
    if ($this->isConnected()) {
      return $this->redis->expire($key, $expire);
    }
  }

}
