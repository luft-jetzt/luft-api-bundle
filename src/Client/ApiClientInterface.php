<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Client;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface ApiClientInterface
{
    public function put(string $uri, array $options = []): ResponseInterface;
    public function post(string $uri, array $options = []): ResponseInterface;
    public function get(string $uri, array $options = []): ResponseInterface;
}
