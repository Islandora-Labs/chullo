<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\FedoraApi;

class ModifyResourceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers  Islandora\Chullo\FedoraApi::modifyResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsTrueOn204()
    {
        $mock = new MockHandler([
            new Response(204),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        $result = $api->modifyResource("");
        $this->assertEquals(204, $result->getStatusCode());
    }

    /**
     * @covers  Islandora\Chullo\FedoraApi::modifyResource
     * @uses    GuzzleHttp\Client
     *
     * TODO: Is this useful anymore?
     */
    public function testReturnsFalseOtherwise()
    {
        $mock = new MockHandler([
            new Response(412),
            new Response(404),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);


        $result = $api->modifyResource("");
        $this->assertEquals(412, $result->getStatusCode());

        $result = $api->modifyResource("");
        $this->assertEquals(404, $result->getStatusCode());
    }
}
