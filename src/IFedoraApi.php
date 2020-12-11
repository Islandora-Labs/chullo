<?php

/**
 * This file is part of Islandora.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category Islandora
 * @package  Islandora
 * @author   Daniel Lamb <dlamb@islandora.ca>
 * @author   Nick Ruest <ruestn@gmail.com>
 * @author   Diego Pino <dpino@metro.org>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     http://www.islandora.ca
 */

namespace Islandora\Chullo;

use Psr\Http\Message\ResponseInterface;

// phpcs:disable
if (version_compare(PHP_VERSION, '7.4.0', 'lt')) {
    class_alias('\EasyRdf_Graph', ' \EasyRdf\Graph');
}
// phpcs:enable

/**
 * Interface for Fedora interaction.  All functions return a PSR-7 response.
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
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResource(
        $uri = "",
        $headers = []
    );

    /**
     * Gets a Fedora resoure's headers.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResourceHeaders(
        $uri = "",
        $headers = []
    );

    /**
     * Gets information about the supported HTTP methods, etc., for a Fedora resource.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResourceOptions(
        $uri = "",
        $headers = []
    );

    /**
     * Creates a new resource in Fedora.
     *
     * @param string    $uri                  Resource URI
     * @param string    $content              String or binary content
     * @param array     $headers              HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createResource(
        $uri = "",
        $content = null,
        $headers = []
    );

    /**
     * Saves a resource in Fedora.
     *
     * @param string    $uri                  Resource URI
     * @param string    $content              String or binary content
     * @param array     $headers              HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function saveResource(
        $uri,
        $content = null,
        $headers = []
    );

    /**
     * Modifies a resource using a SPARQL Update query.
     *
     * @param string    $uri            Resource URI
     * @param string    $sparql         SPARQL Update query
     * @param array     $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function modifyResource(
        $uri,
        $sparql = "",
        $headers = []
    );

    /**
     * Issues a DELETE request to Fedora.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function deleteResource(
        $uri = "",
        $headers = []
    );

    /**
     * Saves RDF in Fedora.
     *
     * @param EasyRdf_Resource  $rdf            Graph to save
     * @param string            $uri            Resource URI
     * @param array             $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function saveGraph(
        \EasyRdf\Graph $graph,
        $uri = "",
        $headers = []
    );

    /**
     * Creates a version of the resource in Fedora.
     *
     * @param string    $uri            Resource URI
     * @param string    $timestamp      Timestamp in RFC-1123 format
     * @param array     $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createVersion(
        $uri = "",
        $timestamp = "",
        $content = null,
        $headers = []
    );

    /**
     * Creates a version of the resource in Fedora.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getVersions(
        $uri = "",
        $headers = []
    );
}
