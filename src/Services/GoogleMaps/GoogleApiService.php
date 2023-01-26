<?php

namespace YouCan\Services\GoogleMaps;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

$dotenv = Dotenv::createImmutable(__DIR__.'/../../..');
$dotenv->load();
class GoogleApiService implements ApiServiceInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ApiServiceImpl constructor.
     *
     * @param Client          $client
     * @param LoggerInterface $logger
     */
    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * Call an API and return array from JSON response.
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     */
    public function get(string $endpoint, array $params): array
    {
        $attempts = 0;
        $maxAttempts = env('MAX_ATTEMPTS');
        $backoff = 2;
        while ($attempts < $maxAttempts) {
            try {
                $this->logger->debug("Calling API endpoint: " . $endpoint . " with params: " . json_encode($params));
                $response = $this->client->get($endpoint, [
                    'query' => $params
                ]);
                return json_decode($response->getBody()->getContents(), true);
            } catch (ConnectException $e) {
                $this->logger->error("API call failed with endpoint: " . $endpoint . " and params: " . json_encode($params) . " Exception: " . $e->getMessage());
                $attempts++;
                if ($attempts < $maxAttempts) {
                    sleep($backoff);
                    $backoff *= 2;
                } else {
                    throw $e;
                }
            }
        }
    }
}
