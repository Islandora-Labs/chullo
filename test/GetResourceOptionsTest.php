<?php

namespace Islandora\Chullo\Test;

use donatj\MockWebServer\Response;
use Islandora\Chullo\FedoraApi;

class GetResourceOptionsTest extends ChulloTestBase
{

    /**
     * @covers  \Islandora\Chullo\FedoraApi::getResourceOptions
     * @uses    \GuzzleHttp\Client
     */
    public function testReturnsHeadersOn200()
    {
        $headers = [
            'Status' => '200 OK',
            'Accept-Patch' => 'application/sparql-update',
            'Allow' => 'MOVE,COPY,DELETE,POST,HEAD,GET,PUT,PATCH,OPTIONS',
            'Accept-Post' => 'text/turtle,text/rdf+n3,application/n3,text/n3,application/rdf+xml,' .
                'application/n-triples,multipart/form-data,application/sparql-update',
        ];
        $test_uri = self::$webserver->setResponseOfPath(
            "/test_options",
            new Response("", $headers, 200)
        );
        $api = FedoraApi::create(self::$webserver->getHost());

        $result = $api->getResourceOptions($test_uri);
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals($headers['Allow'], $result->getHeaderLine('allow'));
        $this->assertEquals($headers['Accept-Patch'], $result->getHeaderLine('accept-patch'));
        $this->assertEquals($headers['Accept-Post'], $result->getHeaderLine('accept-post'));
    }
}
