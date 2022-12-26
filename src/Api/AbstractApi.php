<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Api;

use Caldera\LuftApiBundle\Client\ApiClientInterface;
use JMS\Serializer\SerializerInterface;

abstract class AbstractApi
{
    public function __construct(protected ApiClientInterface $client, protected SerializerInterface $serializer)
    {

    }
}
