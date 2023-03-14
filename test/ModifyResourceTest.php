<?php

namespace Islandora\Chullo\Test;

use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use Islandora\Chullo\FedoraApi;

class ModifyResourceTest extends ChulloTestBase
{

    /**
     * @covers  \Islandora\Chullo\FedoraApi::modifyResource
     * @uses    \GuzzleHttp\Client
     */
    public function testReturnsTrueOn204()
    {
        $test_uri = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource",
            new ResponseByMethod([
                ResponseByMethod::METHOD_PATCH => new Response(
                    "",
                    [],
                    204
                ),
            ])
        );

        $api = FedoraApi::create(self::$webserver->getHost());

        $result = $api->modifyResource($test_uri);
        $this->assertEquals(204, $result->getStatusCode());
    }
}
