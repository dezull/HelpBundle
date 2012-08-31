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
            ->add('category', 'entity', array(
                'class' => 'DezullHelpBundle:HelpCategory',
                'property' => 'name',
            ))
            ->add('content', 'ckeditor', array(
                'transformers'           => array('strip_js', 'strip_css', 'strip_comments', 'strip_tags'),
                'toolbar'                => array('document', 'insert', 'basicstyles', 'styles', 'paragraph'),
                'toolbar_groups'         => array(
                    'document' => array('Source'),
                    'insert' => array('Image'),
                    'styles' => array('Format'),
                ),
                'ui_color'               => '#fff',
                'startup_outline_blocks' => false,
                'width'                  => $options['width'],
                'height'                 => $options['height'],
                'language'               => $options['language'],
                'filebrowser_image_upload_url'   => $options['filebrowser_image_upload_url'],
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options = array())
    {
        $c = $this->container;

        return array(
            'width' => $c->getParameter('dezull_help.topic.content.width'),
            'height' => $c->getParameter('dezull_help.topic.content.height'),
            'language' => $c->getParameter('dezull_help.topic.content.language'),
            'filebrowser_image_upload_url' => $c->get('router')->generate(
                $c->getParameter('dezull_help.topic.content.filebrowser_image_upload_url')),
        );
    }

    public function getName()
    {
        return 'dezull_helpbundle_helptopictype';
    }
}
