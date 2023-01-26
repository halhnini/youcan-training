<?php

namespace YouCan\Tests\Services\GoogleMaps;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use YouCan\Services\GoogleMaps\GoogleApiService;

class ApiServiceTest extends TestCase
{
    public function test_api_service_retries_three_times_before_failing_to_connect_to_host()
    {
        $mock = new MockHandler([
            new ConnectException("Error Communicating with Server", new Request('GET', 'test')),
            new ConnectException("Error Communicating with Server", new Request('GET', 'test')),
            new ConnectException("Error Communicating with Server", new Request('GET', 'test')),
            new Response(200, [], '{"data":"data"}'),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $logger = new Logger('test');
        $apiService = new GoogleApiService($client, $logger);

        try {
            $apiService->get("test",[]);
            $this->fail("Should throw exception after 3 retries");
        } catch (ConnectException $e) {
            $this->assertEquals("Error Communicating with Server", $e->getMessage());
        }
    }
}
