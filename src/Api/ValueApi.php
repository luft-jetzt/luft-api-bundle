<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Api;

use Caldera\LuftModel\Model\Value;

class ValueApi extends AbstractApi implements ValueApiInterface
{
    public function putValue(Value $value): void
    {
        $this->client->put('/api/value', [
            'body' => $this->luftSerializer->serialize($value, 'json'),
        ]);
    }

    public function putValues(array $valueList): void
    {
        // remove keys from $valueList to ensure we build a real json list
        $valueList = array_values($valueList);
        
        $this->client->put('/api/value', [
            'body' => $this->luftSerializer->serialize($valueList, 'json'),
        ]);
    }
}
