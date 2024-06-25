<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Api;

use Caldera\LuftApiBundle\Client\ApiClientInterface;
use Caldera\LuftApiBundle\Serializer\LuftSerializerInterface;

abstract class AbstractApi
{
    public function __construct(
        protected LuftSerializerInterface $luftSerializer,
        protected ApiClientInterface $client
    ) {

    }
}
