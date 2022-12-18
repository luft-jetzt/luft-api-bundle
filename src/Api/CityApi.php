<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Api;

use Caldera\LuftModel\Model\City;

class CityApi extends AbstractApi implements CityApiInterface
{
    public function getCities(): array
    {
        $response = $this->client->get('/api/city');

        $type = sprintf('array<%s>', City::class);
        $cityList = $this->serializer->deserialize($response->getBody()->getContents(), $type, self::SERIALIZER_FORMAT);

        $assocCityList = [];

        /** @var City $city */
        foreach ($cityList as $city) {
            $assocCityList[$city->getSlug()] = $city;
        }

        return $assocCityList;
    }
}
