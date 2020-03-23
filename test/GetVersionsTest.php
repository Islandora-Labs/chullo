<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\FedoraApi;
use PHPUnit\Framework\TestCase;
use \RuntimeException;

class GetVersionsTest extends TestCase
{

    /**
     * @covers  Islandora\Chullo\FedoraApi::getVersions
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsVersionsOn200()
    {

        $headers = [
            'Status' => '200 OK',
            'Link' => '<http://localhost:8080/rest/path/to/resource/fcr:versions>;rel="timemap"'
        ];

        $mock = new MockHandler(
            [
            new Response(200, $headers),
            new Response(200, $headers)
            ]
        );

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        $result = $api->getVersions();

        $this->assertEquals(200, $result->getStatusCode());
    }

    /**
     * @covers  Islandora\Chullo\FedoraApi::getVersions Exception
     * @uses    GuzzleHttp\Client
     */
    public function testThrowErrorWithNoTimemapURI()
    {
        $headers = [
            'Status' => '200 OK'
        ];

        $mock = new MockHandler(
            [
                new Response(200, $headers)
            ]
        );
        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        $this->expectException(\RuntimeException::class);
        $result = $api->getVersions();
    }
}
