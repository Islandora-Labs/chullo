<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\Chullo;
use Islandora\Chullo\FedoraApi;

namespace Islandora\Chullo;

class GetResourceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers  Islandora\Fedora\Chullo::getResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsContentOn200()
    {
        $mock = new MockHandler([
            new Response(200, [], "SOME CONTENT"),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);
        $client = new Chullo($api);
        $result = $client->getResource("");
        $this->assertSame((string)$result, "SOME CONTENT");
    }

    /**
     * @covers  Islandora\Fedora\FedoraApi::getResource
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
     * @covers  Islandora\Fedora\Chullo::getResource
     * @uses    GuzzleHttp\Client
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
        $client = new Chullo($api);

        foreach ($mock as $response) {
            $result = $client->getResource("");
            $this->assertFalse($result);
        }
    }
}
