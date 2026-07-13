<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Tests\Api;

use Caldera\LuftApiBundle\Api\StationApi;
use Caldera\LuftApiBundle\Client\ApiClientInterface;
use Caldera\LuftApiBundle\Serializer\LuftSerializer;
use Caldera\LuftModel\Model\Station;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;

class StationApiTest extends TestCase
{
    private function responseReturning(string $content): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn($content);

        return $response;
    }

    public function testGetStationsWithoutProviderUsesPlainPathAndKeysByStationCode(): void
    {
        $json = json_encode([
            ['station_code' => 'AAA', 'title' => 'A'],
            ['station_code' => 'BBB', 'title' => 'B'],
        ], JSON_THROW_ON_ERROR);

        $client = $this->createMock(ApiClientInterface::class);
        $client->expects(self::once())
            ->method('get')
            ->with('/api/station')
            ->willReturn($this->responseReturning($json));

        $api = new StationApi(new LuftSerializer(), $client);
        $stations = $api->getStations();

        self::assertArrayHasKey('AAA', $stations);
        self::assertArrayHasKey('BBB', $stations);
        self::assertInstanceOf(Station::class, $stations['AAA']);
        self::assertSame('A', $stations['AAA']->getTitle());
    }

    public function testGetStationsWithProviderPassesProviderQuery(): void
    {
        $client = $this->createMock(ApiClientInterface::class);
        $client->expects(self::once())
            ->method('get')
            ->with('/api/station?provider=uba')
            ->willReturn($this->responseReturning('[]'));

        $api = new StationApi(new LuftSerializer(), $client);

        self::assertSame([], $api->getStations('uba'));
    }

    public function testPutStationsStripsKeysToProduceJsonList(): void
    {
        $stations = [
            'AAA' => (new Station())->setStationCode('AAA'),
            'BBB' => (new Station())->setStationCode('BBB'),
        ];

        $capturedBody = null;
        $client = $this->createMock(ApiClientInterface::class);
        $client->expects(self::once())
            ->method('put')
            ->with('/api/station', self::callback(function (array $options) use (&$capturedBody): bool {
                $capturedBody = $options['body'] ?? null;

                return true;
            }))
            ->willReturn($this->createMock(ResponseInterface::class));

        $api = new StationApi(new LuftSerializer(), $client);
        $api->putStations($stations);

        $decoded = json_decode((string) $capturedBody, true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey(0, $decoded, 'body must be a JSON list, not an object keyed by station code');
        self::assertSame('AAA', $decoded[0]['station_code']);
        self::assertSame('BBB', $decoded[1]['station_code']);
    }

    public function testPostStationsSendsOneRequestPerStationToPerCodePath(): void
    {
        $stations = [
            (new Station())->setStationCode('AAA'),
            (new Station())->setStationCode('BBB'),
        ];

        $calls = [];
        $client = $this->createMock(ApiClientInterface::class);
        $client->expects(self::exactly(2))
            ->method('post')
            ->willReturnCallback(function (string $uri, array $options) use (&$calls): ResponseInterface {
                $calls[] = ['uri' => $uri, 'body' => $options['body'] ?? null];

                return $this->createMock(ResponseInterface::class);
            });

        $api = new StationApi(new LuftSerializer(), $client);
        $api->postStations($stations);

        self::assertSame('/api/station/AAA', $calls[0]['uri']);
        self::assertSame('/api/station/BBB', $calls[1]['uri']);
        $body0 = json_decode((string) $calls[0]['body'], true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('AAA', $body0['station_code']);
    }
}
