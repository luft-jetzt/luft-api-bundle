<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Api;

use Doctrine\Common\Annotations\AnnotationReader;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;

abstract class AbstractApi
{
    const SERIALIZER_FORMAT = 'json';

    protected Client $client;
    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->client = new Client([
            'base_uri' => 'https://localhost:8000/',
            'verify' => false,
        ]);

        $this->serializer = $serializer;

        // @see https://github.com/symfony/symfony/issues/29161
        AnnotationReader::addGlobalIgnoredName('alias');
    }
}
