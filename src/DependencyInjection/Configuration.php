<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
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
            ->integerNode('timeout')->defaultValue(10)->min(0)->end()
            ->integerNode('max_duration')->defaultValue(30)->min(0)->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}