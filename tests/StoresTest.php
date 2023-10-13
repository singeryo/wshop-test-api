<?php

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/**
 * TODO: complete this test suite with data fixtures to test get by id or put or delete
 */
class StoresTest extends TestCase
{
    public function getClient(): Client
    {
        return new Client([
            'base_uri' => 'http://wshop-api.test',
            'timeout'  => 2.0,
        ]);
    }

    public function testCanGetAllStores()
    {
        $client = $this->getClient();
        $storesResult = $client->get('/stores');

        $this->assertEquals(200, $storesResult->getStatusCode());
        $this->assertJson($storesResult->getBody());
    }

    public function testCanCreateStore()
    {
        $client = $this->getClient();
        $storesResult = $client->post('/stores', [
            'json' => [
                'store' => [
                    'name' => 'Some test store',
                    'address' => 'Some 456 Test Street'
                ]
            ]
        ]);

        $this->assertEquals(200, $storesResult->getStatusCode());
        $this->assertJson($storesResult->getBody());
    }
}
