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
 * @author   Diego Pino <dpino@metro.org>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL
 * @link     http://www.islandora.ca
 */

namespace Islandora\Chullo;

use GuzzleHttp\Client;

/**
 * Default implementation of IFedoraClient
 *
 * @category Islandora
 * @package  Islandora
 * @author   Daniel Lamb <daniel@discoverygarden.ca>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GPL
 * @link     http://www.islandora.ca
 */
class Chullo implements IFedoraClient
{

    protected $api; // IFedoraApi

    /**
     * @codeCoverageIgnore
     */
    public function __construct(IFedoraApi $api)
    {
        $this->api = $api;
    }

    public static function create($fedora_rest_url)
    {
        $api = FedoraApi::create($fedora_rest_url);
        return new static($api);
    }

    /**
     * Gets the Fedora base uri (e.g. http://localhost:8080/fcrepo/rest)
     *
     * @return string
     */
    public function getBaseUri()
    {
        return $this->api->getBaseUri();
    }

    /**
     * Gets a Fedora resource.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     *
     * @return mixed    Full response if found.  Null otherwise.
     */
    public function getResource(
        $uri = "",
        $headers = [],
        $transaction = ""
    ) {
        $response = $this->api->getResource(
            $uri,
            $headers,
            $transaction
        );
        if ($response->getStatusCode() != 200) {
            return null;
        }

        return (string)$response->getBody();
    }

    /**
     * Gets a Fedora resource's headers.
     *
     * @param string    $uri            Resource URI
     * @param string    $transaction    Transaction id
     *
     * @return array    Headers of a resource, null on failure
     */
    public function getResourceHeaders(
        $uri = "",
        $transaction = ""
    ) {
        $response = $this->api->getResourceHeaders(
            $uri,
            $transaction
        );

        if ($response->getStatusCode() != 200) {
            return null;
        }

        return $response->getHeaders();
    }

    /**
     * Gets information about the supported HTTP methods, etc., for a Fedora resource.
     *
     * @param string    $uri            Resource URI
     *
     * @return string   Options of a resource.
     */
    public function getResourceOptions($uri = "")
    {
        $response = $this->api->getResourceOptions(
            $uri
        );

        return $response->getHeaders();
    }

    /**
     * Gets RDF metadata from Fedora.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     *
     * @return EasyRdf_Graph    EasyRdf_Graph if found, null otherwise
     */
    public function getGraph(
        $uri = "",
        $headers = [],
        $transaction = ""
    ) {
        $headers['Accept'] = 'application/ld+json';
        $rdf = $this->getResource($uri, $headers, $transaction);
        if (empty($rdf)) {
            return null;
        }

        $graph = new \EasyRdf_Graph();
        $graph->parse($rdf, 'jsonld');
        return $graph;
    }

    /**
     * Creates a new resource in Fedora.
     *
     * @param string    $uri            Resource URI
     * @param string    $content        String or binary content
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     * @param string    $checksum       SHA-1 checksum
     *
     * @return string   Uri of newly created resource or null if failed
     */
    public function createResource(
        $uri = "",
        $content = null,
        $headers = [],
        $transaction = "",
        $checksum = ""
    ) {
        $response = $this->api->createResource(
            $uri,
            $content,
            $headers,
            $transaction,
            $checksum
        );

        if ($response->getStatusCode() != 201) {
            return null;
        }

        // Return the value of the location header
        $locations = $response->getHeader('Location');
        return reset($locations);
    }

    /**
     * Saves a resource in Fedora.
     *
     * @param string    $uri            Resource URI
     * @param string    $content        String or binary content
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     * @param string    $checksum       SHA-1 checksum
     *
     * @return boolean  True if successful
     */
    public function saveResource(
        $uri,
        $content = null,
        $headers = [],
        $transaction = "",
        $checksum = ""
    ) {
        $response = $this->api->saveResource(
            $uri,
            $content,
            $headers,
            $transaction,
            $checksum
        );

        return $response->getStatusCode() == 204;
    }

    /**
     * Saves RDF in Fedora.
     *
     * @param string            $uri            Resource URI
     * @param EasyRdf_Resource  $graph          Graph to save
     * @param string            $transaction    Transaction id
     *
     * @return boolean  True if successful
     */
    public function saveGraph(
        $uri,
        \EasyRdf_Graph $graph,
        $transaction = ""
    ) {
        // Serialze the rdf.
        $turtle = $graph->serialise('turtle');

        // Checksum it.
        $checksum = sha1($turtle);

        // Save it.
        return $this->saveResource(
            $uri,
            $turtle,
            ['Content-Type' => 'text/turtle'],
            $transaction,
            $checksum
        );
    }

    /**
     * Modifies a resource using a SPARQL Update query.
     *
     * @param string    $uri            Resource URI
     * @param string    $sparql         SPARQL Update query
     * @param array     $headers        HTTP Headers
     * @param string    $transaction    Transaction id
     *
     * @return boolean  True if successful
     */
    public function modifyResource(
        $uri,
        $sparql = "",
        $headers = [],
        $transaction = ""
    ) {
        $response = $this->api->modifyResource(
            $uri,
            $sparql,
            $headers,
            $transaction
        );

        return $response->getStatusCode() == 204;
    }

    /**
     * Issues a DELETE request to Fedora.
     *
     * @param string    $uri            Resource URI
     * @param string    $transaction    Transaction id
     *
     * @return boolean  True if successful
     */
    public function deleteResource(
        $uri,
        $transaction = ""
    ) {
        $response = $this->api->deleteResource(
            $uri,
            $transaction
        );

        return $response->getStatusCode() == 204;
    }

    /**
     * Issues a COPY request to Fedora.
     *
     * @param string    $uri            Resource URI
     * @param string    $destination    Destination URI
     * @param string    $transaction    Transaction id
     *
     * @return string   Uri of newly created copy or null if failed
     */
    public function copyResource(
        $uri,
        $destination,
        $transaction = ""
    ) {
        $response = $this->api->copyResource(
            $uri,
            $destination,
            $transaction
        );

        if ($response->getStatusCode() != 201) {
            return null;
        }

        // Return the value of the location header
        $locations = $response->getHeader('Location');
        return reset($locations);
    }

    /**
     * Issues a MOVE request to Fedora.
     *
     * @param string    $uri            Resource URI
     * @param string    $destination    Destination URI
     * @param string    $transaction    Transaction id
     *
     * @return string   Uri of moved resource or null if failed
     */
    public function moveResource(
        $uri,
        $destination,
        $transaction = ""
    ) {
        $response = $this->api->moveResource(
            $uri,
            $destination,
            $transaction
        );

        if ($response->getStatusCode() != 201) {
            return null;
        }

        // Return the value of the location header
        $locations = $response->getHeader('Location');
        return reset($locations);
    }

    /**
     * Creates a new transaction.
     *
     * @return null|string   Transaction id or null if failure
     */
    public function createTransaction()
    {
        // Create the transaction.
        $uri = $this->createResource("fcr:tx");

        if (empty($uri)) {
            return null;
        }

        // Hack the tx id out of the response uri.
        $trimmed = rtrim($uri, '/');
        $exploded = explode('/', $trimmed);
        return array_pop($exploded);
    }

    /**
     * Extends a transaction.
     *
     * @param string    $id Transaction id
     *
     * @return boolean  True if successful
     */
    public function extendTransaction($id)
    {
        $response = $this->api->extendTransaction(
            $id
        );

        return $response->getStatusCode() == 204;
    }

    /**
     * Commits a transaction.
     *
     * @param string    $id Transaction id
     *
     * @return boolean  True if successful
     */
    public function commitTransaction($id)
    {
        $response = $this->api->commitTransaction(
            $id
        );

        return $response->getStatusCode() == 204;
    }

    /**
     * Rolls back a transaction.
     *
     * @param string    $id Transaction id
     *
     * @return boolean  True if successful
     */
    public function rollbackTransaction($id)
    {
        $response = $this->api->rollbackTransaction(
            $id
        );

        return $response->getStatusCode() == 204;
    }
}
