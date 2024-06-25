<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Client;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiClient implements ApiClientInterface
{
    protected HttpClientInterface $httpClient;

    public function __construct(string $hostname, int $port, bool $verify)
    {
        $this->httpClient = HttpClient::create([
            'base_uri' => sprintf('https://%s:%d/', $hostname, $port),
            'verify_peer' => $verify,
            'verify_host' => $verify,
        ]);
    }

    public function put($uri, array $options = []): ResponseInterface
    {
        return $this->httpClient->request('PUT', $uri, $options);
    }

    public function post($uri, array $options = []): ResponseInterface
    {
        return $this->httpClient->request('GET', $uri, $options);
    }

    public function get($uri, array $options = []): ResponseInterface
    {
        return $this->httpClient->request('GET', $uri, $options);
    }
}
