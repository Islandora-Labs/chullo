<?php

/**
 * This file is part of Islandora.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP Version 5.5.9
 *
 * @category Islandora
 * @package  Islandora
 * @author   Daniel Lamb <dlamb@islandora.ca>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     http://www.islandora.ca
 */

namespace Islandora\Chullo\Uuid;

use Ramsey\Uuid\Uuid;

/**
 * Generator for v4 & v5 UUIDs.
 */
class UuidGenerator implements IUuidGenerator
{
    /**
     * @var string $namespace
     *   The UUID for this namespace.
     */
    protected $namespace;

    /**
     * @param string $namespace
     *   The initial namespace for the Uuid Generator.
     *
     *  @codeCoverageIgnore
     */
    public function __construct($namespace = null)
    {
        // Give sensible default namespace if none is provided.
        if (empty($namespace)) {
            $namespace = "islandora.ca";
        }
        
        // If we are passed a namespace UUID don't generate it.
        if (Uuid::isValid($namespace)) {
            $this->namespace = $namespace;
        } // Otherwise generate a namespace UUID from the passed in namespace.
        else {
            $this->namespace = Uuid::uuid5(Uuid::NAMESPACE_DNS, $namespace);
        }
    }

    /**
     * Generates a v4 UUID.
     *
     * @return String   Valid v4 UUID.
     */
    public function generateV4()
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * Generates a v5 UUID.
     *
     * @param string $str
     *   The word to generate the UUID with.
     * @param string $namespace
     *   A namespace
     * @return String   Valid v5 UUID.
     */
    public function generateV5($str, $namespace = null)
    {
        // Use default namespace if none is provided.
        if (!empty($namespace)) {
            // Is this a UUID already?
            if (Uuid::isValid($namespace)) {
                return Uuid::uuid5($namespace, $str)->toString();
            } else {
                return Uuid::uuid5(Uuid::uuid5(Uuid::NAMESPACE_DNS, $namespace), $str)->toString();
            }
        } else {
            return Uuid::uuid5($this->namespace, $str)->toString();
        }
    }
}
