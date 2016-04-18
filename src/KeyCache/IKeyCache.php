<?php
/**
 * @file
 * Part of the Chullo service
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Islandora\Chullo\KeyCache;

/**
 * Interface for key -> value service to store UUID -> Fedora Paths
 *  not yet indexed into the triplestore (ie. in a transaction)
 * @author Jared Whiklo <jwhiklo@gmail.com>
 * @since 2016-04-12
 */
abstract class IKeyCache {
  
  /**
   * Sets the value for the provided key.
   *
   * @var string $key
   *   The key.
   * @var mixed $value
   *   The value to set.
   * @var int $expire
   *   The number of seconds from now to expire. Default to an hour.
   * @return bool
   *   Was key set successful.
   */
  public function setKey($key, $value, $expire = 3600);

  /**
   * Gets the value for the provided key.
   *
   * @var string $key
   *   The key.
   * @return mixed
   *   The value corresponding to the key.
   */
  public function getKey($key);
  
  /**
   * Delete the key/value pair for a provided key.
   *
   * @var string $key
   *   The key.
   * @return void
   */
  public function deleteKey($key);
  
  /**
   * Set/reset the key to expire in $seconds seconds.
   *
   * @var string $key
   *   The key.
   * @var int $seconds
   *   The number of seconds from now to expire.
   * @return bool
   *   Whether the expiry was set or not.
   */
  public function extend($key, $seconds);

  /**
   * Set/reset the key to expire at $timestamp.
   *
   * @var string $key
   *   The key.
   * @var DateTime $timestamp
   *   The DateTime when the key should expire.
   * @return bool
   *   Whether the expiry was set or not.
   */
  public function expireAt($key, DateTime $timestamp);
  
}
