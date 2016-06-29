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
 * @author   Nick Ruest <ruestn@gmail.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL
 * @link     http://www.islandora.ca
 */

namespace Islandora\Chullo;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface for Fedora interaction.  All functions return a PSR-7 response.
 *
 * @category Islandora
 * @package  Islandora
 * @author   Daniel Lamb <daniel@discoverygarden.ca>
 * @author   Diego Pino <dpino@metro.org>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL
 * @link     http://www.islandora.ca
 */
interface IFedoraApi
{
    /**
     * Gets the Fedora base uri (e.g. http://localhost:8080/fcrepo/rest)
     *
     * @return string
     */
    public function getBaseUri();

    /**
     * Gets a Fedora resource.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     */
    public function getResource(
        $uri = "",
        $headers = [],
        $transaction = ""
    );
    /**
     * Gets a Fedora resoure's headers.
     *
     * @param string    $uri            Resource URI
     * @param string    $transaction    Transaction id
     */
    public function getResourceHeaders(
        $uri = "",
        $transaction = ""
    );
    /**
     * Gets information about the supported HTTP methods, etc., for a Fedora resource.
     *
     * @param string    $uri            Resource URI
     */
    public function getResourceOptions($uri = "");

    /**
     * Creates a new resource in Fedora.
     *
     * @param string    $uri            Resource URI
     * @param string    $content        String or binary content
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     * @param string    $checksum       SHA-1 checksum
     */
    public function createResource(
        $uri = "",
        $content = null,
        $headers = [],
        $transaction = "",
        $checksum = ""
    );

    /**
     * Saves a resource in Fedora.
     *
     * @param string    $uri            Resource URI
     * @param string    $content        String or binary content
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     * @param string    $checksum       SHA-1 checksum
     */
    public function saveResource(
        $uri,
        $content = null,
        $headers = [],
        $transaction = "",
        $checksum = ""
    );

    /**
     * Modifies a resource using a SPARQL Update query.
     *
     * @param string    $uri            Resource URI
     * @param string    $sparql         SPARQL Update query
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     */
    public function modifyResource(
        $uri,
        $sparql = "",
        $headers = [],
        $transaction = ""
    );

    /**
     * Issues a DELETE request to Fedora.
     *
     * @param string    $uri            Resource URI
     * @param string    $transaction    Transaction id
     */
    public function deleteResource(
        $uri,
        $transaction = ""
    );
    /**
     * Issues a COPY request to Fedora.
     *
     * @param string    $uri            Resource URI
     * @param array     $destination    Destination URI
     * @param string    $transaction    Transaction id
     */
    public function copyResource(
        $uri,
        $destination,
        $transaction = ""
    );
    /**
     * Issues a MOVE request to Fedora.
     *
     * @param string    $uri            Resource URI
     * @param array     $destination    Destination URI
     * @param string    $transaction    Transaction id
     */
    public function moveResource(
        $uri,
        $destination,
        $transaction = ""
    );

    /**
     * Creates a new transaction.
     */
    public function createTransaction();

    /**
     * Extends a transaction.
     *
     * @param string    $id Transaction id
     */
    public function extendTransaction($id);

    /**
     * Commits a transaction.
     *
     * @param string    $id Transaction id
     */
    public function commitTransaction($id);

    /**
     * Rolls back a transaction.
     *
     * @param string    $id Transaction id
     */
    public function rollbackTransaction($id);
}
