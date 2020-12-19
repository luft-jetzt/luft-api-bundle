<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('caldera_luftapi');

        $treeBuilder
            ->getRootNode()
            ->children()
            ->arrayNode('api')
            ->children()
            ->scalarNode('hostname')->cannotBeEmpty()->end()
            ->scalarNode('port')->end()
            ->booleanNode('verify')->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}