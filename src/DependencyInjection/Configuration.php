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
            ->isRequired()
            ->children()
            ->scalarNode('hostname')->isRequired()->cannotBeEmpty()->end()
            ->integerNode('port')->min(1)->max(65535)->defaultValue(443)->end()
            ->booleanNode('verify')->defaultTrue()->end()
            ->scalarNode('token')->defaultValue('')->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}