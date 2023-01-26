<?php

namespace YouCan\Tests\Services\GoogleMaps;

use PHPUnit\Framework\TestCase;
use YouCan\Entities\LocationCollection;
use YouCan\Services\GoogleMaps\ApiServiceInterface;
use YouCan\Services\GoogleMaps\LocationService;

class FindLocationServiceTest extends TestCase
{
    public function test_search_location_return_location_collection()
    {
        $apiService = $this->createMock(ApiServiceInterface::class);
        $apiService->method('get')->willReturn($this->getData());
        $findLocationService = new LocationService($apiService);

        $locationCollection = $findLocationService->searchLocation("test");
        $this->assertInstanceOf(LocationCollection::class, $locationCollection);
    }

    private function getData()
    {
        return [
            "results" => [
                [
                    "formatted_address" => "address 1",
                    "geometry" => [
                        "location" => [
                            "lat" => "12.55998",
                            "lng" => "15.5555"
                        ],
                    ],
                    "place_id" => "114563",
                ],
                [
                    "formatted_address" => "address 2",
                    "geometry" => [
                        "location" => [
                            "lat" => "12.55998",
                            "lng" => "15.5555"
                        ],
                    ],
                    "place_id" => "54896",
                ]
            ]
        ];
    }
}
