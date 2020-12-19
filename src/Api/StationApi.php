<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Api;

use Caldera\LuftApiBundle\Model\Station;

class StationApi extends AbstractApi implements StationApiInterface
{
    public function getStations(): array
    {
        $response = $this->client->get('/api/station?provider=uba_de');

        $type = sprintf('array<%s>', Station::class);
        $stationList = $this->serializer->deserialize($response->getBody()->getContents(), $type, self::SERIALIZER_FORMAT);

        $assocStationList = [];

        /** @var Station $station */
        foreach ($stationList as $station) {
            $assocStationList[$station->getStationCode()] = $station;
        }

        return $assocStationList;
    }

    public function putStations(array $stationList): void
    {
        $this->client->put('/api/station', [
            'body' => $this->serializer->serialize($stationList, self::SERIALIZER_FORMAT),
        ]);
    }

    public function postStations(array $stationList): void
    {
        /** @var Station $station */
        foreach ($stationList as $station) {
            $postApiUrl = sprintf('/api/station/%d', $station->getStationCode());

            $this->client->post($postApiUrl, [
                'body' => $this->serializer->serialize($station, self::SERIALIZER_FORMAT),
            ]);
        }
    }
}
