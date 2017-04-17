<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\FedoraApi;

class GetResourceHeadersTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers  Islandora\Chullo\FedoraApi::getResourceHeaders
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsHeadersOn200()
    {

        $headers = [
            'Status' => '200 OK',
            'ETag' => "bbdd92e395800153a686773f773bcad80a51f47b",
            'Last-Modified' => 'Wed, 28 May 2014 18:31:36 GMT',
            'Link' => '<http://www.w3.org/ns/ldp#Resource>;rel="type"',
            'Link' => '<http://www.w3.org/ns/ldp#Container>;rel="type"',
            'Link' => '<http://www.w3.org/ns/ldp#BasicContainer>;rel="type"',
            'Accept-Patch' => 'application/sparql-update',
            'Accept-Post' => 'text/turtle,text/rdf+n3,text/n3,application/rdf+xml,application/n-triples,' .
                'multipart/form-data,application/sparql-update',
            'Allow' => 'MOVE,COPY,DELETE,POST,HEAD,GET,PUT,PATCH,OPTIONS',
        ];

        $mock = new MockHandler(
            [
            new Response(200, $headers)
            ]
        );

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        $result = $api->getResourceHeaders("");

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertTrue($result->hasHeader("etag"));
        $this->assertEquals("bbdd92e395800153a686773f773bcad80a51f47b", $result->getHeaderLine("ETag"));
    }
}
