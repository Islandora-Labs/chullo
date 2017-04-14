<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\FedoraApi;

class GetResourceOptionsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers  Islandora\Chullo\FedoraApi::getResourceOptions
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsHeadersOn200()
    {
        $headers = [
            'Status' => '200 OK',
            'Accept-Patch' => 'application/sparql-update',
            'Allow' => 'MOVE,COPY,DELETE,POST,HEAD,GET,PUT,PATCH,OPTIONS',
            'Accept-Post' => 'text/turtle,text/rdf+n3,application/n3,text/n3,application/rdf+xml,' .
                'application/n-triples,multipart/form-data,application/sparql-update',
        ];
        $mock = new MockHandler([
          new Response(200, $headers),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        $result = $api->getResourceOptions("");
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals($headers['Allow'], $result->getHeaderLine('allow'));
        $this->assertEquals($headers['Accept-Patch'], $result->getHeaderLine('accept-patch'));
        $this->assertEquals($headers['Accept-Post'], $result->getHeaderLine('accept-post'));
    }
}
