<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Api;

use Caldera\LuftApiBundle\Model\Value;

interface ValueApiInterface
{
    public function putValue(Value $value): void;
    public function putValues(array $valueList): void;
}
