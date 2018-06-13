<?php
namespace SubDir;

use GuzzleHttp\Client;

/**
 * Class RestClient
 * @package SubDir
 */
class RestClient
{
    /** @var Client */
    private $client;

    /** @var string */
    private $host;

    /**
     * @param string $host
     * @param Client $client
     */
    public function __construct(string $host, Client $client)
    {
        $this->host = $host;
        $this->client = $client;
    }

    /**
     * @return ItemsCollection
     * @throws ApiResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getItems(bool $available = false): ItemsCollection
    {
        $url = $this->host . "/api/items?filter[available]=" . ($available ? 'true' : 'false');
        $result = $this->client->request('GET', $url);

        if (200 !== $result->getStatusCode()) {
            throw new ApiResponseException('Incorrect response');
        }

        $collection = new ItemsCollection();
        $items = json_decode($result->getBody()->getContents(), true);

        foreach ($items as $item) {
            $collection->push(new Item($item['id'], $item['name'], $item['amount']));
        }

        return $collection;
    }
}
