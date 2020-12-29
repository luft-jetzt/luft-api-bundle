<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Api;

use Caldera\LuftApiBundle\Client\ApiClientInterface;
use JMS\Serializer\SerializerInterface;

abstract class AbstractApi
{
    protected SerializerInterface $serializer;
    protected ApiClientInterface $client;

    public function __construct(ApiClientInterface $client, SerializerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }
}
