<?php

namespace Islandora\Chullo\Test;

use Islandora\Chullo\FedoraApi;

class GetBaseUriTest extends ChulloTestBase
{

    /**
     * @covers  \Islandora\Chullo\FedoraApi::getBaseUri
     * @uses    \GuzzleHttp\Client
     */
    public function testReturnsUri()
    {
        $input = 'http://localhost:8080/fcrepo/rest';
        $api = FedoraApi::create($input);
        # Base Uri always has a forward slash on the end.
        $expected = 'http://localhost:8080/fcrepo/rest/';

        $baseUri = $api->getBaseUri();
        $this->assertEquals($expected, $baseUri);
    }
}
