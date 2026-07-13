<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Tests\DependencyInjection;

use Caldera\LuftApiBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    public function testProcessFullConfiguration(): void
    {
        $processed = (new Processor())->processConfiguration(new Configuration(), [
            'caldera_luftapi' => [
                'api' => [
                    'hostname' => 'api.luft.jetzt',
                    'port' => 443,
                    'verify' => true,
                ],
            ],
        ]);

        self::assertSame('api.luft.jetzt', $processed['api']['hostname']);
        self::assertSame(443, $processed['api']['port']);
        self::assertTrue($processed['api']['verify']);
    }
}
