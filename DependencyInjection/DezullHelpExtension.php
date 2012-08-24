<?php

namespace Dezull\Bundle\HelpBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DezullHelpExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('dezull_help.topic.type.width', $config['width']);
        $container->setParameter('dezull_help.topic.type.height', $config['height']);
        $container->setParameter('dezull_help.topic.type.language', $config['language']);
        $container->setParameter('dezull_help.topic.type.filebrowser_image_upload_url', $config['filebrowser_image_upload_url']);

    }
}
