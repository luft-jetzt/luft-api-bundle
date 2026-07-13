<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Api;

use Caldera\LuftModel\Model\Station;

class StationApi extends AbstractApi implements StationApiInterface
{
    public function getStations(string $provider = null): array
    {
        $options = [];

        if ($provider) {
            // Let the HttpClient encode the query so characters like &, =, #
            // or spaces in $provider cannot break out of the parameter.
            $options['query'] = ['provider' => $provider];
        }

        $response = $this->client->get('/api/station', $options);

        $type = sprintf('%s[]', Station::class);
        $stationList = $this->luftSerializer->deserialize($response->getContent(), $type, self::SERIALIZER_FORMAT);

        $assocStationList = [];

        /** @var Station $station */
        foreach ($stationList as $station) {
            $stationCode = $station->getStationCode();

            if (null === $stationCode || '' === $stationCode) {
                throw new \UnexpectedValueException('Received a station without a station code from the API; cannot key it uniquely.');
            }

            $assocStationList[$stationCode] = $station;
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
            $stationCode = $station->getStationCode();

            if (null === $stationCode || '' === $stationCode) {
                throw new \InvalidArgumentException('Cannot POST a station without a station code.');
            }

            // rawurlencode the code (from external data sources) so a value
            // containing /, ?, # or spaces cannot alter the request path.
            $postApiUrl = sprintf('/api/station/%s', rawurlencode($stationCode));

            $this->client->post($postApiUrl, [
                'body' => $this->luftSerializer->serialize($station, self::SERIALIZER_FORMAT),
            ]);
        }
    }
}
