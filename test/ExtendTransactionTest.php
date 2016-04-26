<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\Chullo;
use Islandora\Chullo\FedoraApi;

class ExtendTransactionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers  Islandora\Fedora\Chullo::extendTransaction
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
        $client = new Chullo($api);

        $result = $client->extendTransaction("tx:abc-123");
        $this->assertTrue($result);
    }

    /**
     * @covers            Islandora\Fedora\Chullo::extendTransaction
     * @uses              GuzzleHttp\Client
     */
    public function testReturnsFalseOtherwise()
    {
        $mock = new MockHandler([
            new Response(410),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);
        $client = new Chullo($api);

        $result = $client->extendTransaction("tx:abc-123");
        $this->assertFalse($result);
    }
}
