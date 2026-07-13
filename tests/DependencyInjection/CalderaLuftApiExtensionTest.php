<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\Tests\DependencyInjection;

use Caldera\LuftApiBundle\Api\StationApi;
use Caldera\LuftApiBundle\Api\ValueApi;
use Caldera\LuftApiBundle\Client\ApiClient;
use Caldera\LuftApiBundle\DependencyInjection\CalderaLuftApiExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CalderaLuftApiExtensionTest extends TestCase
{
    public function testGetAlias(): void
    {
        self::assertSame('caldera_luftapi', (new CalderaLuftApiExtension())->getAlias());
    }

    public function testLoadRegistersServicesAndWiresApiClientArguments(): void
    {
        $container = new ContainerBuilder();
        $extension = new CalderaLuftApiExtension();

        $extension->load([
            'caldera_luftapi' => [
                'api' => [
                    'hostname' => 'api.luft.jetzt',
                    'port' => 8443,
                    'verify' => true,
                ],
            ],
        ], $container);

        self::assertTrue($container->hasDefinition(ApiClient::class));
        self::assertTrue($container->hasDefinition(StationApi::class));
        self::assertTrue($container->hasDefinition(ValueApi::class));

        $arguments = $container->getDefinition(ApiClient::class)->getArguments();
        self::assertSame('api.luft.jetzt', $arguments[0]);
        self::assertSame(8443, $arguments[1]);
        self::assertTrue($arguments[2]);
    }
}
