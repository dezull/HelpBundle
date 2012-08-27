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

        $container->setParameter('dezull_help.topic.type.class', $config['topic_type']);

        $container->setParameter('dezull_help.topic.content.width', $config['content_editor_width']);
        $container->setParameter('dezull_help.topic.content.height', $config['content_editor_height']);
        $container->setParameter('dezull_help.topic.content.language', $config['content_editor_language']);
        $container->setParameter('dezull_help.topic.content.filebrowser_image_upload_url', $config['image_upload_url']);

        $container->setParameter('dezull_help.image.dir', $config['image_dir']);
        $container->setParameter('dezull_help.image.baseurl', $config['image_baseurl']);
        $container->setParameter('dezull_help.image.mimetypes', $config['image_mimetypes']);
    }
}
