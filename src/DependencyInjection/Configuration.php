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
            ->integerNode('port')->isRequired()->min(1)->max(65535)->end()
            ->booleanNode('verify')->defaultTrue()->end()
            ->scalarNode('auth_bearer')->defaultNull()->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}