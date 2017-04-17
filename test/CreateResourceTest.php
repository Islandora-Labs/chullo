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
        $mock = new MockHandler(
            [
            new Response(201, ['Location' => "SOME URI"]),
            ]
        );

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        $result = $api->createResource("");
        $this->assertEquals($result->getHeaderLine("Location"), "SOME URI");
        $this->assertEquals(201, $result->getStatusCode(), "Expected a 201 response.");
    }
}
