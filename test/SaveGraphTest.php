<?php

namespace Islandora\Chullo\Test;

use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use EasyRdf\Graph;
use Islandora\Chullo\FedoraApi;

class SaveGraphTest extends ChulloTestBase
{

    /**
     * @covers  \Islandora\Chullo\FedoraApi::saveGraph
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

        $result = $api->saveGraph(new Graph(), $test_uri);
        $this->assertEquals(204, $result->getStatusCode());
    }
}
