<?php

namespace Fnx\GuzzleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration
    implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder ()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('fnx_guzzle');

        $rootNode->children()
                     ->arrayNode('clients')
                         ->useAttributeAsKey('name')
                         ->prototype('array')
                             ->children()
                                 ->scalarNode('base_uri')->isRequired()->end()
                                 ->arrayNode('config')->variablePrototype()->end()->end()
                            ->end()
                         ->end()
                     ->end()
                     ->arrayNode('commands')
                         ->useAttributeAsKey('name')
                         ->prototype('array')
                         ->children()
                             ->scalarNode('uri')->isRequired()->end()
                             ->scalarNode('client')->defaultValue('default')->end()
                             ->scalarNode('method')->defaultValue('GET')->end()
                             ->scalarNode('class')->end()
                             ->scalarNode('resultClass')->end()
                             ->enumNode('resultType')->values(
                                [
                                    'array',
                                    'object',
                                    'string',
                                ]
                             )->end()
                             ->arrayNode('defaults')->variablePrototype()->end()
                             ->end()
                             ->arrayNode('params')
                                 ->useAttributeAsKey('name')
                                 ->prototype('array')
                                     ->children()
                                         ->enumNode('location')
                                             ->values(
                                                [
                                                    'json',
                                                    'uri',
                                                    'headers',
                                                    'query',
                                                    'data',
                                                ]
                                             )
                                             ->isRequired()
                                         ->end()
                                         ->scalarNode('map')->end()
                                         ->scalarNode('value')->end()
                                         ->booleanNode('required')->defaultValue(false)->end()
                                         ->booleanNode('static')->defaultValue(false)->end()
                                     ->end()
                                 ->end()
                             ->end()
                         ->end()
                    ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
