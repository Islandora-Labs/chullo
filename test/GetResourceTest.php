<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\FedoraApi;

class GetResourceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers  Islandora\Chullo\FedoraApi::getResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsApiContentOn200()
    {
        $mock = new MockHandler([
            new Response(200, ['X-FOO' => 'Fedora4'], "SOME CONTENT"),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);
        $result = $api->getResource("");
        $this->assertSame((string)$result->getBody(), "SOME CONTENT");
        $this->assertSame($result->getHeader('X-FOO'), ['Fedora4']);
    }

    /**
     * @covers  Islandora\Chullo\FedoraApi::getResource
     * @uses    GuzzleHttp\Client
     *
     * TODO: Is this useful anymore?
     */
    public function testReturnsNullOtherwise()
    {
        $mock = new MockHandler([
            new Response(304),
            new Response(404),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        //304
        $result = $api->getResource("");
        $this->assertEquals(304, $result->getStatusCode());

        //404
        $result = $api->getResource("");
        $this->assertEquals(404, $result->getStatusCode());
    }
}
