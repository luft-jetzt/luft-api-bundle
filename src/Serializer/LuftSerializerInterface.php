<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Serializer;

interface LuftSerializerInterface
{
    public function serialize(mixed $data, string $format, array $context = []): string;
    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed;
}
