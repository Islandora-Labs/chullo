<?php

namespace Islandora\Chullo;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Islandora\Chullo\FedoraApi;
use PHPUnit\Framework\TestCase;

class GetBaseUriTest extends TestCase
{

    /**
     * @covers  Islandora\Chullo\FedoraApi::getBaseUri
     * @uses    GuzzleHttp\Client
     */
    public function testReturnsUri()
    {
        $guzzle = new Client(['base_uri'=>'http://localhost:8080/fcrepo/rest']);
        $api = new FedoraApi($guzzle);

        $baseUri = $api->getBaseUri();
        $this->assertEquals($baseUri, 'http://localhost:8080/fcrepo/rest');
    }
}
