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

use EasyRdf\Graph;
use Psr\Http\Message\ResponseInterface;

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
    public function getBaseUri(): string;

    /**
     * Gets a Fedora resource.
     *
     * @param string $uri            Resource URI
     * @param array  $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResource(
        string $uri = "",
        array $headers = []
    ): ResponseInterface;

    /**
     * Gets a Fedora resoure's headers.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResourceHeaders(
        string $uri = "",
        array $headers = []
    ): ResponseInterface;

    /**
     * Gets information about the supported HTTP methods, etc., for a Fedora resource.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResourceOptions(
        string $uri = "",
        array $headers = []
    ): ResponseInterface;

    /**
     * Creates a new resource in Fedora.
     *
     * @param string      $uri                  Resource URI
     * @param mixed|null $content              String, resource from fopen() or stream content
     * @param array       $headers              HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createResource(
        string $uri = "",
        $content = null,
        array $headers = []
    ): ResponseInterface;

    /**
     * Saves a resource in Fedora.
     *
     * @param string      $uri                  Resource URI
     * @param mixed|null $content               String, resource from fopen() or stream content
     * @param array       $headers              HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function saveResource(
        string $uri,
        $content = null,
        array $headers = []
    ): ResponseInterface;

    /**
     * Modifies a resource using a SPARQL Update query.
     *
     * @param string    $uri            Resource URI
     * @param string    $sparql         SPARQL Update query
     * @param array     $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function modifyResource(
        string $uri,
        string $sparql = "",
        array $headers = []
    ): ResponseInterface;

    /**
     * Issues a DELETE request to Fedora.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function deleteResource(
        string $uri = "",
        array $headers = []
    ): ResponseInterface;

    /**
     * Saves RDF in Fedora.
     *
     * @param \EasyRdf\Graph    $graph          Graph to save
     * @param string            $uri            Resource URI
     * @param array             $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function saveGraph(
        Graph $graph,
        string $uri = "",
        array $headers = []
    ): ResponseInterface;

    /**
     * Creates RDF in Fedora.
     *
     * @param \EasyRdf\Graph $graph    Graph to save
     * @param string         $uri      Resource URI
     * @param array          $headers  HTTP Headers
     *
     * @return ResponseInterface
     */
    public function createGraph(
        Graph $graph,
        string $uri = '',
        array $headers = []
    ): ResponseInterface;

    /**
     * Gets RDF in Fedora.
     *
     * @param ResponseInterface $response Response received
     *
     * @return \EasyRdf\Graph
     * @throws \EasyRdf\Exception Unable to parse graph.
     */
    public function getGraph(ResponseInterface $response): Graph;

    /**
     * Creates a version of the resource in Fedora.
     *
     * @param string      $uri            Resource URI
     * @param string      $timestamp      Timestamp in RFC-1123 format
     * @param string|null $content        Content for the version
     * @param array       $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createVersion(
        string $uri = "",
        string $timestamp = "",
        ?string $content = null,
        array $headers = []
    ): ResponseInterface;

    /**
     * Creates a version of the resource in Fedora.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getVersions(
        string $uri = "",
        array $headers = []
    ): ResponseInterface;
}
