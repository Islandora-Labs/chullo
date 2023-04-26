<?php

namespace Islandora\Chullo\Test;

use DateTime;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use Islandora\Chullo\FedoraApi;

class CreateVersionTest extends ChulloTestBase
{

    /**
     * @covers  \Islandora\Chullo\FedoraApi::createVersion
     * @uses    \GuzzleHttp\Client
     */
    public function testReturns201withVersions()
    {
        $test_uri_timemap = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource/fcr:versions",
            new ResponseByMethod([
                ResponseByMethod::METHOD_POST => new Response("", ['Location' => "SOME URI"], 201)
            ])
        );
        $test_uri = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource",
            new ResponseByMethod([
                ResponseByMethod::METHOD_HEAD => new Response(
                    "",
                    ['Link' => "<$test_uri_timemap>; rel=\"timemap\""],
                    200
                )
            ])
        );

        $api = FedoraApi::create(self::$webserver->getHost());
        $date = new DateTime();
        $timestamp = $date->format("D, d M Y H:i:s O");
        $content = "test";
        $result = $api->createVersion($test_uri, $timestamp, $content);
        $this->assertEquals(201, $result->getStatusCode());
    }

    /**
     * @covers  \Islandora\Chullo\FedoraApi::createVersion Exception
     * @uses    \GuzzleHttp\Client
     */
    public function testThrowsExceptionWithoutTimemapUri()
    {
        $test_uri = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource",
            new ResponseByMethod([
                ResponseByMethod::METHOD_GET => new Response(
                    "",
                    [],
                    200
                )
            ])
        );
        $api = FedoraApi::create(self::$webserver->getHost());

        $this->expectException(\RuntimeException::class);
        $api->createVersion($test_uri);
    }
}
