<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\DependencyInjection;

use Caldera\LuftApiBundle\Client\ApiClient;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CalderaLuftApiExtension extends Extension
{
    public function load(array $defaultConfigs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $defaultConfigs);

        $definition = $container->getDefinition(ApiClient::class);
        $definition->setArgument(0, (string) $config['api']['hostname']);
        $definition->setArgument(1, (int) $config['api']['port']);
        $definition->setArgument(2, (bool) $config['api']['verify']);
    }

    public function getAlias(): string
    {
        return "caldera_luftapi";
    }
}
