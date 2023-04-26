<?php

namespace Islandora\Chullo\Test;

use donatj\MockWebServer\Response;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Islandora\Chullo\FedoraApi;
use Psr\Http\Message\ResponseInterface;

/**
 * Test of the createWithHandler method.
 */
class CreateClientTest extends ChulloTestBase
{
    public function testAddHeader(): void
    {
        $test_uri = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource",
            new Response(
                "SOME CONTENT",
                ['X-FOO' => 'Fedora4'],
                200
            )
        );

        $stack = HandlerStack::create();
        $stack->setHandler(new CurlHandler());

        $stack->push(Middleware::mapResponse(function (ResponseInterface $response) {
            return $response->withHeader('X-ISLANDORA', 'my header');
        }));

        $api = FedoraApi::createWithHandler(self::$webserver->getHost(), $stack);

        $result = $api->getResource($test_uri);
        $this->assertSame((string)$result->getBody(), "SOME CONTENT");
        $this->assertSame($result->getHeader('X-FOO'), ['Fedora4']);
        $this->assertSame($result->getHeader('X-ISLANDORA'), ['my header']);
    }
}
