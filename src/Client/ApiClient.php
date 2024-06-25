<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Client;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class ApiClient implements ApiClientInterface
{
    protected Client $client;

    public function __construct(string $hostname, int $port, bool $verify)
    {
        $this->client = new Client([
            'base_uri' => sprintf('https://%s:%d/', $hostname, $port),
            'verify' => $verify,
        ]);
    }

    public function put($uri, array $options = []): ResponseInterface
    {
        return $this->client->put($uri, $options);
    }

    public function post($uri, array $options = []): ResponseInterface
    {
        return $this->client->post($uri, $options);
    }

    public function get($uri, array $options = []): ResponseInterface
    {
        return $this->client->get($uri, $options);
    }
}
