<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\Chullo;
use Islandora\Chullo\FedoraApi;

class CopyResourceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers  Islandora\Chullo\Chullo::copyResource
     * @covers  Islandora\Chullo\Chullo::copyResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsUriOn201()
    {
        $mock = new MockHandler([
            new Response(201, ['Location' => "http://localhost:8080/fcrepo/rest/SOME_URI"]),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);
        $client = new Chullo($api);

        $result = $client->copyResource("", "");
        $this->assertSame($result, "http://localhost:8080/fcrepo/rest/SOME_URI");
    }

    /**
     * @covers  Islandora\Chullo\Chullo::copyResource
     * @covers  Islandora\Chullo\Chullo::copyResource
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsNullOtherwise()
    {
        $mock = new MockHandler([
            new Response(404),
            new Response(409),
            new Response(502),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);
        $client = new Chullo($api);

        //404
        $result = $client->copyResource("", "");
        $this->assertNull($result);
        
        //409
        $result = $client->copyResource("", "");
        $this->assertNull($result);
        
        //502
        $result = $client->copyResource("", "");
        $this->assertNull($result);
    }
}
