<?php

namespace Islandora\Chullo\Test;

use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use Islandora\Chullo\FedoraApi;

class CreateResourceTest extends ChulloTestBase
{

    /**
     * @covers  \Islandora\Chullo\FedoraApi::createResource
     * @uses    \GuzzleHttp\Client
     */
    public function testReturnsUriOn201()
    {
        $test_uri = self::$webserver->setResponseOfPath(
            '/some_uri',
            new ResponseByMethod([
                ResponseByMethod::METHOD_POST =>
                    new Response(
                        "",
                        ['Location' => "SOME URI"],
                        201
                    )
            ])
        );

        $api = FedoraApi::create(self::$webserver->getHost());

        $result = $api->createResource($test_uri);
        $this->assertEquals($result->getHeaderLine("Location"), "SOME URI");
        $this->assertEquals(201, $result->getStatusCode(), "Expected a 201 response.");
    }
}
