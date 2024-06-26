<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Api;

use Caldera\LuftModel\Model\Station;

class StationApi extends AbstractApi implements StationApiInterface
{
    public function getStations(string $provider = null): array
    {
        if ($provider) {
            $response = $this->client->get(sprintf('/api/station?provider=%s', $provider));
        } else {
            $response = $this->client->get('/api/station');
        }

        $type = sprintf('%s[]', Station::class);
        $stationList = $this->luftSerializer->deserialize($response->getContent(), $type, self::SERIALIZER_FORMAT);

        $assocStationList = [];

        /** @var Station $station */
        foreach ($stationList as $station) {
            $assocStationList[$station->getStationCode()] = $station;
        }

        return $assocStationList;
    }

    public function putStations(array $stationList): void
    {
        // remove keys from $stationList to ensure we build a real json list
        $stationList = array_values($stationList);

        $this->client->put('/api/station', [
            'body' => $this->luftSerializer->serialize($stationList, self::SERIALIZER_FORMAT),
        ]);
    }

    public function postStations(array $stationList): void
    {
        /** @var Station $station */
        foreach ($stationList as $station) {
            $postApiUrl = sprintf('/api/station/%s', $station->getStationCode());

            $this->client->post($postApiUrl, [
                'body' => $this->luftSerializer->serialize($station, self::SERIALIZER_FORMAT),
            ]);
        }
    }
}
