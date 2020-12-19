<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\DependencyInjection;

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
    }

    public function getAlias(): string
    {
        return "caldera_luftapi";
    }
}