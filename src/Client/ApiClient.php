<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiClient implements ApiClientInterface
{
    public function __construct(
        protected HttpClientInterface $httpClient
    ) {
    }

    public function put(string $uri, array $options = []): ResponseInterface
    {
        return $this->httpClient->request('PUT', $uri, $options);
    }

    public function post(string $uri, array $options = []): ResponseInterface
    {
        return $this->httpClient->request('POST', $uri, $options);
    }

    public function get(string $uri, array $options = []): ResponseInterface
    {
        return $this->httpClient->request('GET', $uri, $options);
    }
}
