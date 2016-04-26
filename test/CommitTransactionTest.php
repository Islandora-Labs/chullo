<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\Chullo;
use Islandora\Chullo\FedoraApi;

namespace Islandora\Chullo;

class CommitTransactionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers  Islandora\Fedora\Chullo::commitTransaction
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsTrue204()
    {
        $mock = new MockHandler([
            new Response(204),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);
        $client = new Chullo($api);

        $result = $client->commitTransaction("tx:abc-123");
        $this->assertTrue($result);
    }

    /**
     * @covers            Islandora\Fedora\Chullo::commitTransaction
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

        $result = $client->commitTransaction("tx:abc-123");
        $this->assertFalse($result);
    }
}
