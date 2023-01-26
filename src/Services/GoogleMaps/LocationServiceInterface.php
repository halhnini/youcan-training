<?php

namespace Youcan\Services\GoogleMaps;

use Youcan\Entities\LocationCollection;

interface LocationServiceInterface
{
    public function __construct(ApiServiceInterface $apiService);

    /**
     * Search for a location using the Google Maps API.
     *
     * @param string $terms Terms to search with
     * @return LocationCollection
     *
     * @see https://developers.google.com/maps/documentation/places/web-service/search-text
     */
    public function searchLocation(string $terms): LocationCollection;
}
