<?php

namespace Islandora\Chullo\Test;

use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use EasyRdf\Graph;
use Islandora\Chullo\FedoraApi;

class CreateGraphTest extends ChulloTestBase
{

    /**
     * @covers  \Islandora\Chullo\FedoraApi::createGraph
     * @uses    \GuzzleHttp\Client
     */
    public function testReturnsTrueOn204()
    {
        $test_uri = self::$webserver->setResponseOfPath(
            "/test_create",
            new ResponseByMethod([
                ResponseByMethod::METHOD_POST =>
                    new Response(
                        "",
                        [],
                        204
                    )
            ])
        );

        $api = FedoraApi::create(self::$webserver->getHost());

        $result = $api->createGraph(new Graph(), $test_uri);
        $this->assertEquals(204, $result->getStatusCode());
    }
}
