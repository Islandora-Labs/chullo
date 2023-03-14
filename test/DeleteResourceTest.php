<?php

namespace Islandora\Chullo\Test;

use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use Islandora\Chullo\FedoraApi;

class DeleteResourceTest extends ChulloTestBase
{

    /**
     * @covers  \Islandora\Chullo\FedoraApi::deleteResource
     * @uses    \GuzzleHttp\Client
     */
    public function testReturnsTrueOn204()
    {
        $test_uri = self::$webserver->setResponseOfPath(
            '/rest/some/path',
            new ResponseByMethod([
                ResponseByMethod::METHOD_DELETE =>
                    new Response(
                        "",
                        [],
                        204
                    ),
            ])
        );

        $api = FedoraApi::create(self::$webserver->getHost());

        $result = $api->deleteResource($test_uri);
        $this->assertEquals(204, $result->getStatusCode());
    }
}
