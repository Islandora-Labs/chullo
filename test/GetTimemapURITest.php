<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\FedoraApi;
use PHPUnit\Framework\TestCase;

class GetTimemapURITest extends TestCase
{

    /**
     * @covers  Islandora\Chullo\FedoraApi::getTimemapURI
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsTimemapHeaderOn200()
    {

        $headers = [
            'Status' => '200 OK',
            'ETag' => "bbdd92e395800153a686773f773bcad80a51f47b",
            'Last-Modified' => 'Wed, 28 May 2014 18:31:36 GMT',
            'Link' => '<http://www.w3.org/ns/ldp#Resource>;rel="type"',
            'Link' => '<http://www.w3.org/ns/ldp#Container>;rel="type"',
            'Link' => '<http://localhost:8080/rest/path/to/resource/fcr:versions>;rel="timemap"',
        ];

        $mock = new MockHandler(
            [
            new Response(200, $headers)
            ]
        );

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        $timemapuri = $api->getTimemapURI("");

        $this->assertEquals("http://localhost:8080/rest/path/to/resource/fcr:versions", $timemapuri);
    }
}
