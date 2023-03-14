<?php

namespace Islandora\Chullo\Test;

use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use Islandora\Chullo\FedoraApi;

class SaveResourceTest extends ChulloTestBase
{

    /**
     * @covers  \Islandora\Chullo\FedoraApi::saveResource
     * @uses    \GuzzleHttp\Client
     */
    public function testReturnsTrueOn204()
    {
        $test_uri = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource",
            new ResponseByMethod([
                ResponseByMethod::METHOD_PUT => new Response("", [], 204),
            ])
        );
        $api = FedoraApi::create(self::$webserver->getHost());

        $result = $api->saveResource($test_uri);
        $this->assertEquals(204, $result->getStatusCode());
    }
}
