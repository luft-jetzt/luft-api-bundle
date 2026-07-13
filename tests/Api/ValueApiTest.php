<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Tests\Api;

use Caldera\LuftApiBundle\Api\ValueApi;
use Caldera\LuftApiBundle\Client\ApiClientInterface;
use Caldera\LuftApiBundle\Serializer\LuftSerializer;
use Caldera\LuftModel\Model\Value;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ValueApiTest extends TestCase
{
    private function value(string $code): Value
    {
        return (new Value())
            ->setStationCode($code)
            ->setDateTime(new \DateTime('@0'))
            ->setValue(1.0)
            ->setPollutant('no2');
    }

    public function testPutValueSendsSingleJsonObject(): void
    {
        $capturedBody = null;
        $client = $this->createMock(ApiClientInterface::class);
        $client->expects(self::once())
            ->method('put')
            ->with('/api/value', self::callback(function (array $options) use (&$capturedBody): bool {
                $capturedBody = $options['body'] ?? null;

                return true;
            }))
            ->willReturn($this->createMock(ResponseInterface::class));

        $api = new ValueApi(new LuftSerializer(), $client);
        $api->putValue($this->value('S1'));

        $decoded = json_decode((string) $capturedBody, true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayNotHasKey(0, $decoded, 'single value must serialize to a JSON object');
        self::assertSame('S1', $decoded['station_code']);
    }

    public function testPutValuesStripsKeysToProduceJsonList(): void
    {
        $values = [
            'a' => $this->value('S1'),
            'b' => $this->value('S2'),
        ];

        $capturedBody = null;
        $client = $this->createMock(ApiClientInterface::class);
        $client->expects(self::once())
            ->method('put')
            ->with('/api/value', self::callback(function (array $options) use (&$capturedBody): bool {
                $capturedBody = $options['body'] ?? null;

                return true;
            }))
            ->willReturn($this->createMock(ResponseInterface::class));

        $api = new ValueApi(new LuftSerializer(), $client);
        $api->putValues($values);

        $decoded = json_decode((string) $capturedBody, true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey(0, $decoded, 'body must be a JSON list, not an object');
        self::assertCount(2, $decoded);
        self::assertSame('S1', $decoded[0]['station_code']);
    }

    public function testPutValuesWithEmptyListSendsEmptyJsonArray(): void
    {
        $capturedBody = null;
        $client = $this->createMock(ApiClientInterface::class);
        $client->expects(self::once())
            ->method('put')
            ->with('/api/value', self::callback(function (array $options) use (&$capturedBody): bool {
                $capturedBody = $options['body'] ?? null;

                return true;
            }))
            ->willReturn($this->createMock(ResponseInterface::class));

        $api = new ValueApi(new LuftSerializer(), $client);
        $api->putValues([]);

        self::assertSame('[]', (string) $capturedBody);
    }
}
