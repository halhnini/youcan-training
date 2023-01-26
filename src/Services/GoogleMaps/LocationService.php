<?php

namespace YouCan\Services\GoogleMaps;

use Dotenv\Dotenv;
use YouCan\Entities\LocationCollection;

$dotenv = Dotenv::createImmutable(__DIR__.'/../../..');
$dotenv->load();
class LocationService implements LocationServiceInterface
{
    private $apiService;

    public function __construct(ApiServiceInterface $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Search for a location using the Google Maps API.
     *
     * @param string $terms Terms to search with
     * @return LocationCollection
     *
     * @see https://developers.google.com/maps/documentation/places/web-service/search-text
     */
    public function searchLocation(string $terms): LocationCollection
    {
        $response = $this->apiService->get('/places/textsearch/json', [
            'query' => $terms,
            'key' => env('GOOGLE_MAPS_KEY')
        ]);

        return LocationCollection::createFromArray($response['results']);
    }
}
