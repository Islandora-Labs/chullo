<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\FedoraApi;

class CreateGraphTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers  Islandora\Chullo\FedoraApi::createGraph
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

        $result = $api->createGraph(new \EasyRdf_Graph());
        $this->assertEquals(204, $result->getStatusCode());
    }
}
