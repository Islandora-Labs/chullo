<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\Chullo;
use Islandora\Chullo\FedoraApi;

class CreateTransactionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers  Islandora\Chullo\Chullo::createTransaction
     * @covers  Islandora\Chullo\FedoraApi::createTransaction
     * @covers  Islandora\Chullo\FedoraApi::prepareUri
     * @covers  Islandora\Chullo\FedoraApi::baseUri
     * @covers  Islandora\Chullo\FedoraApi::createTransaction
     * @covers  Islandora\Chullo\FedoraApi::generateTransaction
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsIdOn201()
    {
        $mock = new MockHandler([
            new Response(201, ['Location' => "http://localhost:8080/fcrepo/rest/tx:abc-123"]),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler, 'base_uri' => 'http://localhost:8080/fcrepo/rest']);
        $api = new FedoraApi($guzzle);
        $client = new Chullo($api);

        $result = $client->createTransaction();
        $this->assertSame($result, "tx:abc-123");
    }
    /**
     * @covers  Islandora\Chullo\Chullo::createTransaction
     * @covers  Islandora\Chullo\FedoraApi::createTransaction
     * @covers  Islandora\Chullo\FedoraApi::prepareUri
     * @covers  Islandora\Chullo\FedoraApi::baseUri
     * @covers  Islandora\Chullo\FedoraApi::createTransaction
     * @covers  Islandora\Chullo\FedoraApi::generateTransaction
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsNullOtherwise()
    {
        $mock = new MockHandler([
            new Response(404),
        ]);

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler, 'base_uri' => 'http://localhost:8080/fcrepo/rest']);
        $api = new FedoraApi($guzzle);
        $client = new Chullo($api);

        //404
        $result = $client->createTransaction();
        $this->assertNull($result);
    }
}
