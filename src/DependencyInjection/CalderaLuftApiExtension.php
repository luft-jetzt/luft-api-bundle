<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\DependencyInjection;

use Caldera\LuftApiBundle\Client\ApiClient;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CalderaLuftApiExtension extends Extension
{
    private const string HTTP_CLIENT_SERVICE_ID = 'caldera_luftapi.http_client';

    public function load(array $defaultConfigs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $defaultConfigs);

        $httpClientOptions = [
            'base_uri' => sprintf('https://%s:%d/', (string) $config['api']['hostname'], (int) $config['api']['port']),
            'verify_peer' => (bool) $config['api']['verify'],
            'verify_host' => (bool) $config['api']['verify'],
            'timeout' => (int) $config['api']['timeout'],
            'max_duration' => (int) $config['api']['max_duration'],
        ];

        $httpClientDefinition = new Definition(HttpClientInterface::class);
        $httpClientDefinition->setFactory([HttpClient::class, 'create']);
        $httpClientDefinition->setArgument(0, $httpClientOptions);
        $httpClientDefinition->setPublic(false);

        $container->setDefinition(self::HTTP_CLIENT_SERVICE_ID, $httpClientDefinition);

        $definition = $container->getDefinition(ApiClient::class);
        $definition->setArgument(0, new Reference(self::HTTP_CLIENT_SERVICE_ID));
    }

    public function getAlias(): string
    {
        return 'caldera_luftapi';
    }
}
