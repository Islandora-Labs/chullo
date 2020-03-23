<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\FedoraApi;
use PHPUnit\Framework\TestCase;
use \RuntimeException;
use \DateTime;

class CreateVersionTest extends TestCase
{

    /**
     * @covers  Islandora\Chullo\FedoraApi::createVersion
     * @uses    GuzzleHttp\Client
     */
    public function testReturns201withVersions()
    {
        $mock = new MockHandler(
            [
            new Response(200, ['Link' => '<http://localhost:8080/rest/path/to/resource/fcr:versions>;rel="timemap"']),
            new Response(201, ['Location' => "SOME URI"])
            ]
        );

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);
        $date = new DateTime();
        $timestamp = $date->format("D, d M Y H:i:s O");
        $content = "test";
        $result = $api->createVersion('', $timestamp, $content);
        $this->assertEquals(201, $result->getStatusCode());
    }

    /**
     * @covers  Islandora\Chullo\FedoraApi::createVersion Exception
     * @uses    GuzzleHttp\Client
     */
    public function testThrowsExceptionWithoutTimemapUri()
    {
        $mock = new MockHandler(
            [
            new Response(200, []),
            new Response(201, ['Location' => "SOME URI"])
            ]
        );

        $handler = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handler]);
        $api = new FedoraApi($guzzle);

        $this->expectException(\RuntimeException::class);
        $result = $api->createVersion('');
    }
}
