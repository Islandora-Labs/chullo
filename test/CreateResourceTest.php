<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\FedoraApi;

class CreateResourceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers  Islandora\Chullo\FedoraApi::createResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsUriOn201()
    {
        $mock = new MockHandler([
            new Response(201, [], "SOME URI"),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        $result = $api->createResource("");
        $this->assertSame((string)$result->getBody(), "SOME URI");
    }
    /**
     * @covers  Islandora\Chullo\FedoraApi::createResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsNullOtherwise()
    {
        $mock = new MockHandler([
            new Response(404),
            new Response(409),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        // 404
        $result = $api->createResource("");
        $this->assertSame((string)$result->getStatusCode(), "404");

        // 409
        $result = $api->createResource("");
        $this->assertSame((string)$result->getStatusCode(), "409");
    }
}
