<?php

namespace Islandora\Chullo\Test;

use donatj\MockWebServer\Response;
use Islandora\Chullo\FedoraApi;

class GetResourceTest extends ChulloTestBase
{

    /**
     * @covers  \Islandora\Chullo\FedoraApi::getResource
     * @uses    \GuzzleHttp\Client
     */
    public function testReturnsApiContentOn200()
    {
        $test_uri = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource",
            new Response(
                "SOME CONTENT",
                ['X-FOO' => 'Fedora4'],
                200
            )
        );

        $api = FedoraApi::create(self::$webserver->getHost());
        $result = $api->getResource($test_uri);
        $this->assertSame((string)$result->getBody(), "SOME CONTENT");
        $this->assertSame($result->getHeader('X-FOO'), ['Fedora4']);
    }

    /**
     * @covers  \Islandora\Chullo\FedoraApi::getResource
     * @uses    \GuzzleHttp\Client
     *
     * TODO: Is this useful anymore?
     */
    public function testReturnsNullOtherwise()
    {
        $test_uri1 = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource1",
            new Response(
                "",
                [],
                304
            )
        );
        $test_uri2 = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource2",
            new Response(
                "",
                [],
                404
            )
        );

        $api = FedoraApi::create(self::$webserver->getHost());

        //304
        $result = $api->getResource($test_uri1);
        $this->assertEquals(304, $result->getStatusCode());

        //404
        $result = $api->getResource($test_uri2);
        $this->assertEquals(404, $result->getStatusCode());
    }
}
