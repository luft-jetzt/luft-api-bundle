<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Client;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface ApiClientInterface
{
    public function put($uri, array $options = []): ResponseInterface;
    public function post($uri, array $options = []): ResponseInterface;
    public function get($uri, array $options = []): ResponseInterface;
}
