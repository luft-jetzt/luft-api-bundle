<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Api;

use Caldera\LuftModel\Model\Value;

class ValueApi extends AbstractApi implements ValueApiInterface
{
    public function putValue(Value $value): void
    {
        $response = $this->client->put('/api/value', [
            'body' => $this->luftSerializer->serialize($value, self::SERIALIZER_FORMAT),
        ]);

        $this->assertSuccessful($response);
    }

    public function putValues(array $valueList): void
    {
        // remove keys from $valueList to ensure we build a real json list
        $valueList = array_values($valueList);

        $response = $this->client->put('/api/value', [
            'body' => $this->luftSerializer->serialize($valueList, self::SERIALIZER_FORMAT),
        ]);

        $this->assertSuccessful($response);
    }
}
