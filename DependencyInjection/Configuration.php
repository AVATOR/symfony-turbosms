<?php

namespace AVATOR\TurbosmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('avator_turbosms');

        $rootNode
            ->children()

                ->scalarNode('login')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()

                ->scalarNode('password')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()

                ->scalarNode('sender')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()

                ->scalarNode('debug')
                    ->defaultFalse()
                    ->end()

                ->scalarNode('save_to_db')
                    ->defaultTrue()
                    ->end();

        return $treeBuilder;
    }
}
