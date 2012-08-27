<?php

namespace Dezull\Bundle\HelpBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dezull_help');

        $rootNode
            ->children()
                ->scalarNode('topic_type')
                    ->defaultValue('Dezull\Bundle\HelpBundle\Form\HelpTopicType')
                ->end()
            ->end()
            ->children()
                ->scalarNode('content_editor_width')
                    ->defaultValue('400')
                ->end()
            ->end()
            ->children()
                ->scalarNode('content_editor_height')
                    ->defaultValue('150')
                ->end()
            ->end()
            ->children()
                ->scalarNode('content_editor_language')
                    ->defaultValue('en_uk')
                ->end()
            ->end()
            ->children()
                ->scalarNode('image_upload_url')
                    ->defaultValue('/help/upload-image')
                ->end()
            ->end()
            ->children()
                ->scalarNode('image_dir')
                    ->defaultValue('%kernel.root_dir%/../web/upload/help')
                ->end()
            ->end()
            ->children()
                ->scalarNode('image_baseurl')
                    ->defaultValue('/upload/help')
                ->end()
            ->end()
            ->children()
                ->variableNode('image_mimetypes')
                    ->defaultValue(array(
                        'image/png',
                        'image/jpeg',
                        'image/gif',
                    ))
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
