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
 * @author   Daniel Lamb <daniel@discoverygarden.ca>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL
 * @link     http://www.islandora.ca
 */

namespace Islandora\Chullo\Uuid;

/**
 * Interface for generating UUIDs.
 *
 * @category Islandora
 * @package  Islandora
 * @author   Daniel Lamb <daniel@discoverygarden.ca>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL
 * @link     http://www.islandora.ca
 */
interface IUuidGenerator
{

    /**
     * Generates a v4 UUID.
     *
     * @return String   Valid v4 UUID.
     */
    public function generateV4();

    /**
     * Generates a v5 UUID.
     *
     * @return String   Valid v5 UUID.
     */
    public function generateV5($name, $namespace = null);
}
