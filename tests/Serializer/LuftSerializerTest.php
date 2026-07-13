<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Tests\Serializer;

use Caldera\LuftApiBundle\Serializer\LuftSerializer;
use Caldera\LuftModel\Model\Station;
use Caldera\LuftModel\Model\Value;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class LuftSerializerTest extends TestCase
{
    private LuftSerializer $serializer;

    protected function setUp(): void
    {
        $this->serializer = new LuftSerializer();
    }

    public function testSerializeValueUsesSnakeCaseKeysAndUnixTimestamp(): void
    {
        $value = (new Value())
            ->setStationCode('DE-BW-001')
            ->setDateTime(new \DateTime('2026-01-02 03:04:05', new \DateTimeZone('UTC')))
            ->setValue(12.5)
            ->setPollutant('pm10');

        $json = $this->serializer->serialize($value, 'json');
        $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('station_code', $decoded);
        self::assertSame('DE-BW-001', $decoded['station_code']);

        // DateTimeNormalizer is configured with FORMAT 'U' + CAST 'int'
        self::assertArrayHasKey('date_time', $decoded);
        self::assertIsInt($decoded['date_time']);
        self::assertSame((new \DateTime('2026-01-02 03:04:05', new \DateTimeZone('UTC')))->getTimestamp(), $decoded['date_time']);
    }

    public function testSerializeSkipsNullValuesByDefault(): void
    {
        $value = (new Value())
            ->setStationCode('DE-BW-001')
            ->setDateTime(new \DateTime('@0'))
            ->setValue(1.0)
            ->setPollutant('no2');
        // tag stays null

        $decoded = json_decode($this->serializer->serialize($value, 'json'), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayNotHasKey('tag', $decoded);
    }

    public function testDeserializeJsonListToStationArray(): void
    {
        $json = json_encode([
            ['station_code' => 'AAA', 'title' => 'Station A'],
            ['station_code' => 'BBB', 'title' => 'Station B'],
        ], JSON_THROW_ON_ERROR);

        $type = sprintf('%s[]', Station::class);
        $stations = $this->serializer->deserialize($json, $type, 'json');

        self::assertIsArray($stations);
        self::assertCount(2, $stations);
        self::assertContainsOnlyInstancesOf(Station::class, $stations);
        self::assertSame('AAA', $stations[0]->getStationCode());
        self::assertSame('Station B', $stations[1]->getTitle());
    }

    public function testDeserializeEmptyListYieldsEmptyArray(): void
    {
        $type = sprintf('%s[]', Station::class);
        $stations = $this->serializer->deserialize('[]', $type, 'json');

        self::assertSame([], $stations);
    }

    public function testValueRoundtrip(): void
    {
        $value = (new Value())
            ->setStationCode('RT-1')
            ->setDateTime(new \DateTime('2026-06-01 00:00:00', new \DateTimeZone('UTC')))
            ->setValue(42.0)
            ->setPollutant('o3')
            ->setTag('roundtrip');

        $json = $this->serializer->serialize($value, 'json');
        /** @var Value $restored */
        $restored = $this->serializer->deserialize($json, Value::class, 'json');

        self::assertSame('RT-1', $restored->getStationCode());
        self::assertSame(42.0, $restored->getValue());
        self::assertSame('o3', $restored->getPollutant());
        self::assertSame('roundtrip', $restored->getTag());
        self::assertNotNull($restored->getDateTime());
        self::assertSame($value->getDateTime()->getTimestamp(), $restored->getDateTime()->getTimestamp());
    }

    /**
     * Regression test for the context-override bug tracked in #34: on the
     * current main the serializer merges its DEFAULT_CONTEXT *after* the
     * caller context (`array_merge($context, self::DEFAULT_CONTEXT)`), so an
     * explicit `SKIP_NULL_VALUES => false` is silently ignored and null
     * properties are still dropped. This test asserts the desired behaviour
     * and is skipped until #34 corrects the merge order.
     */
    public function testCallerContextSkipNullValuesFalseIsRespected(): void
    {
        self::markTestSkipped('Blocked by #34: DEFAULT_CONTEXT currently overrides the caller context (array_merge order).');

        // @phpstan-ignore-next-line intentionally unreachable until #34
        $value = (new Value())->setStationCode('X')->setDateTime(new \DateTime('@0'))->setValue(1.0)->setPollutant('no2');

        $decoded = json_decode(
            $this->serializer->serialize($value, 'json', [AbstractObjectNormalizer::SKIP_NULL_VALUES => false]),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        self::assertArrayHasKey('tag', $decoded);
        self::assertNull($decoded['tag']);
    }
}
