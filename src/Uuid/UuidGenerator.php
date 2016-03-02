<?php

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
     */
    public function __construct($namespace = NULL) {
        // Give sensible default namespace if none is provided.
        if (empty($namespace)) {
            $namespace = "islandora.ca";
        }
        
        // If we are passed a namespace UUID don't generate it.
        if (Uuid::isValid($namespace)) {
          $this->namespace = $namespace;
        }
        // Otherwise generate a namespace UUID from the passed in namespace.
        else {
          $this->namespace = Uuid::uuid5(Uuid::NAMESPACE_DNS, $namespace);
        }
    }

    /**
     * Generates a v4 UUID.
     *
     * @return String   Valid v4 UUID.
     */
    public function generateV4() {
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
    public function generateV5($str, $namespace = NULL) {
        // Use default namespace if none is provided.
        if (!empty($namespace)) {
          // Is this a UUID already?
          if (Uuid::isValid($namespace)) {
            return Uuid::uuid5($namespace, $str)->toString();
          }
          else {
            return Uuid::uuid5(Uuid::uuid5(Uuid::NAMESPACE_DNS, $namespace), $str)->toString();
          }
        }
        else {
          return Uuid::uuid5($this->namespace, $str)->toString();
        }
    }

}
