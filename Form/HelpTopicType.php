<?php

namespace Dezull\Bundle\HelpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HelpTopicType extends AbstractType
{
    protected $container;
    protected $transformers;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;       
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            // ->add('content')
            ->add('content', 'ckeditor', array(
                'transformers'           => array('strip_js', 'strip_css', 'strip_comments'),
                'toolbar'                => array('document', 'insert', 'basicstyles', 'paragraph'),
                'toolbar_groups'         => array(
                    'document' => array('Source'),
                    'insert' => array('Image'),
                ),
                'ui_color'               => '#fff',
                'startup_outline_blocks' => false,
                'width'                  => $options['width'],
                'height'                 => $options['height'],
                'language'               => $options['language'],
                'filebrowser_image_upload_url'   => $options['filebrowser_image_upload_url'],
            ))
            ->add('category', 'entity', array(
                'class' => 'DezullHelpBundle:HelpCategory',
                'property' => 'name',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options = array())
    {
        return array(
            'width' => $this->container->getParameter('dezull_help.topic.type.width'),
            'height' => $this->container->getParameter('dezull_help.topic.type.height'),
            'language' => $this->container->getParameter('dezull_help.topic.type.language'),
            'filebrowser_image_upload_url' => $this->container->getParameter('dezull_help.topic.type.filebrowser_image_upload_url'),
        );
    }

    public function getName()
    {
        return 'dezull_helpbundle_helptopictype';
    }
}
