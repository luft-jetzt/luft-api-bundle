<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Api;

use Caldera\LuftApiBundle\Client\ApiClientInterface;
use Caldera\LuftApiBundle\Exception\LuftApiException;
use Caldera\LuftApiBundle\Exception\LuftApiTransportException;
use Caldera\LuftApiBundle\Serializer\LuftSerializerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractApi
{
    protected const string SERIALIZER_FORMAT = 'json';

    public function __construct(
        protected LuftSerializerInterface $luftSerializer,
        protected ApiClientInterface $client
    ) {

    }

    /**
     * Consume a (lazily executed) response and fail loudly on error.
     *
     * Symfony's HttpClient is lazy: without touching the response the
     * request is only completed in the response destructor, where any
     * error would be thrown out of a destructor. Calling getStatusCode()
     * here forces completion and lets us surface a meaningful exception.
     *
     * @throws LuftApiTransportException on transport-level failures
     * @throws LuftApiException          on HTTP status >= 400
     */
    protected function assertSuccessful(ResponseInterface $response): void
    {
        try {
            $statusCode = $response->getStatusCode();
        } catch (TransportExceptionInterface $exception) {
            throw new LuftApiTransportException('Transport error while calling the Luft API.', 0, $exception);
        }

        if ($statusCode >= 400) {
            throw new LuftApiException(sprintf('Luft API returned HTTP status %d.', $statusCode));
        }
    }
}
