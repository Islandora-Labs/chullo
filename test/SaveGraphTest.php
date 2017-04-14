<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\FedoraApi;

class SaveGraphTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers  Islandora\Chullo\FedoraApi::saveGraph
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

        $result = $api->saveGraph(new \EasyRdf_Graph());
        $this->assertEquals(204, $result->getStatusCode());
    }

    /**
     * @covers            Islandora\Chullo\FedoraApi::saveGraph
     * @uses              GuzzleHttp\Client
     * @expectedException \GuzzleHttp\Exception\ClientException
     *
     * TODO: Is this useful anymore?
     */
    public function testReturnsExceptionOn412()
    {
        $mock = new MockHandler([
            new Response(412),
            new Response(409),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        $result = $api->saveGraph(new \EasyRdf_Graph());
        $this->assertEquals(412, $result->getStatusCode());

        $result = $api->saveGraph(new \EasyRdf_Graph());
        $this->assertEquals(409, $result->getStatusCode());
    }

    /**
     * @covers            Islandora\Chullo\FedoraApi::saveGraph
     * @uses              GuzzleHttp\Client
     * @expectedException \GuzzleHttp\Exception\ClientException
     *
     * TODO: Is this useful anymore?
     */
    public function testReturnsExceptionOn409()
    {
        $mock = new MockHandler([
            new Response(409),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        $result = $api->saveGraph(new \EasyRdf_Graph());
        $this->assertEquals(409, $result->getStatusCode());
    }
}
