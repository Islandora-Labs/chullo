<?php

namespace Islandora\Chullo\Test;

use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use Islandora\Chullo\FedoraApi;

class GetVersionsTest extends ChulloTestBase
{

    /**
     * @covers  \Islandora\Chullo\FedoraApi::getVersions
     * @uses    \GuzzleHttp\Client
     */
    public function testReturnsVersionsOn200()
    {
        $test_uri_timemap = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource/fcr:versions",
            new ResponseByMethod([
                ResponseByMethod::METHOD_GET =>
                new Response(
                    "",
                    [],
                    200
                )
            ])
        );
        $headers = [
          'Status' => '200 OK',
          'Link' => "<$test_uri_timemap>;rel=\"timemap\""
        ];
        $test_uri = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource",
            new ResponseByMethod([
                ResponseByMethod::METHOD_HEAD =>
                new Response(
                    "",
                    $headers,
                    200
                )
            ])
        );

        $api = FedoraApi::create(self::$webserver->getHost());

        $result = $api->getVersions($test_uri);

        $this->assertEquals(200, $result->getStatusCode());
    }

    /**
     * @covers  \Islandora\Chullo\FedoraApi::getVersions Exception
     * @uses    \GuzzleHttp\Client
     */
    public function testThrowErrorWithNoTimemapURI()
    {
        $headers = [
            'Status' => '200 OK'
        ];
        $test_uri = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource",
            new ResponseByMethod([
                ResponseByMethod::METHOD_HEAD =>
                new Response(
                    "",
                    $headers,
                    200
                )
            ])
        );

        $api = FedoraApi::create(self::$webserver->getHost());

        $this->expectException(\RuntimeException::class);
        $api->getVersions($test_uri);
    }
}
