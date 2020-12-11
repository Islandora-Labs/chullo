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
 * @author   Jared Whiklo <Jared.Whiklo@umanitoba.ca>
 * @author   Diego Pino <dpino@metro.org>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     http://www.islandora.ca
 */

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use \RuntimeException;

// phpcs:disable
if (version_compare(PHP_VERSION, '7.4.0', 'lt')) {
    class_alias('\EasyRdf_Graph', ' \EasyRdf\Graph');
}
// phpcs:enable

//
/**
 * Default implementation of IFedoraApi using Guzzle.
 */
class FedoraApi implements IFedoraApi
{

    protected $client;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function create($fedora_rest_url)
    {
        $normalized = rtrim($fedora_rest_url);
        $normalized = rtrim($normalized, '/') . '/';
        $guzzle = new Client(['base_uri' => $normalized]);
        return new static($guzzle);
    }

    /**
     * Gets the Fedora base uri (e.g. http://localhost:8080/fcrepo/rest)
     *
     * @return string
     */
    public function getBaseUri()
    {
        return $this->client->getConfig('base_uri');
    }

    /**
     * Gets a Fedora resource.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     *
     * @return ResponseInterface
     */
    public function getResource(
        $uri = "",
        $headers = []
    ) {
        // Set headers
        $options = ['http_errors' => false, 'headers' => $headers];

        // Send the request.
        return $this->client->request(
            'GET',
            $uri,
            $options
        );
    }

    /**
     * Gets a Fedora resoure's headers.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     *
     * @return ResponseInterface
     */
    public function getResourceHeaders(
        $uri = "",
        $headers = []
    ) {

        // Send the request.
        return $this->client->request(
            'HEAD',
            $uri,
            ['http_errors' => false, 'headers' => $headers]
        );
    }

    /**
     * Gets information about the supported HTTP methods, etc., for a Fedora resource.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     *
     * @return ResponseInterface
     */
    public function getResourceOptions(
        $uri = "",
        $headers = []
    ) {
        return $this->client->request(
            'OPTIONS',
            $uri,
            ['http_errors' => false, 'headers' => $headers]
        );
    }

    /**
     * Creates a new resource in Fedora.
     *
     * @param string    $uri                  Resource URI
     * @param string    $content              String or binary content
     * @param array     $headers              HTTP Headers
     *
     * @return ResponseInterface
     */
    public function createResource(
        $uri = "",
        $content = null,
        $headers = []
    ) {
        $options = ['http_errors' => false];

        // Set content.
        $options['body'] = $content;

        // Set headers.
        $options['headers'] = $headers;

        return $this->client->request(
            'POST',
            $uri,
            $options
        );
    }

    /**
     * Saves a resource in Fedora.
     *
     * @param string    $uri                  Resource URI
     * @param string    $content              String or binary content
     * @param array     $headers              HTTP Headers
     *
     * @return ResponseInterface
     */
    public function saveResource(
        $uri,
        $content = null,
        $headers = []
    ) {
        $options = ['http_errors' => false];

        // Set content.
        $options['body'] = $content;

        // Set headers.
        $options['headers'] = $headers;

        return $this->client->request(
            'PUT',
            $uri,
            $options
        );
    }

    /**
     * Modifies a resource using a SPARQL Update query.
     *
     * @param string    $uri            Resource URI
     * @param string    $sparql         SPARQL Update query
     * @param array     $headers        HTTP Headers
     *
     * @return ResponseInterface
     */
    public function modifyResource(
        $uri,
        $sparql = "",
        $headers = []
    ) {
        $options = ['http_errors' => false];

        // Set content.
        $options['body'] = $sparql;

        // Set headers.
        $options['headers'] = $headers;
        $options['headers']['content-type'] = 'application/sparql-update';

        return $this->client->request(
            'PATCH',
            $uri,
            $options
        );
    }

    /**
     * Issues a DELETE request to Fedora.
     *
     * @param string    $uri            Resource URI
     * @param array     $headers        HTTP Headers
     *
     * @return ResponseInterface
     */
    public function deleteResource(
        $uri = '',
        $headers = []
    ) {
        $options = ['http_errors' => false, 'headers' => $headers];

        return $this->client->request(
            'DELETE',
            $uri,
            $options
        );
    }

    /**
     * Saves RDF in Fedora.
     *
     * @param EasyRdf_Resource  $graph          Graph to save
     * @param string            $uri            Resource URI
     * @param array             $headers        HTTP Headers
     *
     * @return ResponseInterface
     */
    public function saveGraph(
        \EasyRdf\Graph $graph,
        $uri = '',
        $headers = []
    ) {
        // Serialze the rdf.
        $turtle = $graph->serialise('turtle');

        // Checksum it.
        $checksum_value = sha1($turtle);

        // Set headers.
        $headers['Content-Type'] = 'text/turtle';
        $headers['digest'] = 'sha1=' . $checksum_value;

        // Save it.
        return $this->saveResource($uri, $turtle, $headers);
    }

    /**
     * Creates RDF in Fedora.
     *
     * @param EasyRdf_Resource  $graph          Graph to save
     * @param string            $uri            Resource URI
     * @param array             $headers        HTTP Headers
     *
     * @return ResponseInterface
     */
    public function createGraph(
        \EasyRdf\Graph $graph,
        $uri = '',
        $headers = []
    ) {
        // Serialze the rdf.
        $turtle = $graph->serialise('turtle');

        // Checksum it.
        $checksum_value = sha1($turtle);

        // Set headers.
        $headers['Content-Type'] = 'text/turtle';
        $headers['digest'] = 'sha1=' . $checksum_value;

        // Save it.
        return $this->createResource($uri, $turtle, $headers);
    }


    /**
     * Gets RDF in Fedora.
     *
     * @param ResponseInterface   $request    Response received
     *
     * @return \EasyRdf\Graph
     */
    public function getGraph(ResponseInterface $response)
    {
        // Extract rdf as response body and return Easy_RDF Graph object.
        $rdf = $response->getBody()->getContents();
        $graph = new \EasyRdf\Graph();
        if (!empty($rdf)) {
            $graph->parse($rdf, 'jsonld');
        }
        return $graph;
    }

    /**
     * Creates version in Fedora.
     * @param string $uri Fedora Resource URI
     * @param string $timestamp Timestamp for Memento version
     * @param string $content String or binary content
     * @param array $header HTTP Headers
     *
     * @return ResponseInterface
     */
    public function createVersion(
        $uri = '',
        $timestamp = '',
        $content = null,
        $headers = []
    ) {
        $timemap_uri = $this->getTimemapURI($uri, $headers);
        if ($timemap_uri == null) {
            throw new \RuntimeException('Timemap URI is null, cannot create version');
        }
        $options = ['http_errors' => false];
        if ($timestamp != '' && $content != null) {
            $headers['Memento-Datetime'] = $timestamp;
            $options['body'] = $content;
        }
        $options['headers'] = $headers;

        return $this->client->request(
            'POST',
            $timemap_uri,
            $options
        );
    }

    /**
     * Gets list of versions in Fedora.
     * @param string $uri Fedora Resource URI
     * @param array $header HTTP Headers
     *
     * @return ResponseInterface
     */
    public function getVersions(
        $uri = '',
        $headers = []
    ) {
        $timemap_uri = $this->getTimemapURI($uri, $headers);
        if ($timemap_uri == null) {
            throw new \RuntimeException('Timemap URI is null, cannot create version');
        }
        $options = ['http_errors' => false, 'headers' => $headers];
        return $this->client->request(
            'GET',
            $timemap_uri,
            $options
        );
    }

    /**
     * Helper method to get the Headers for a resource
     * and parse the timemap header from it
     * @param string $uri Fedora Resource URI
     * @param array $header HTTP Headers
     *
     * @return string
     */
    public function getTimemapURI(
        $uri = '',
        $headers = []
    ) {
        $resource_headers = $this->getResourceHeaders($uri, $headers);
        $parsed_link_headers = Psr7\parse_header($resource_headers->getHeader('Link'));
        $timemap_uri = null;
        $timemap_index = array_search('timemap', array_column($parsed_link_headers, 'rel'));
        if (is_int($timemap_index)) {
            $timemap_uri = $parsed_link_headers[$timemap_index][0];
            $timemap_uri = trim($timemap_uri, "<> \t\n\r\0\x0B");
        }
        return $timemap_uri;
    }
}
