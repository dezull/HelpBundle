<?php

namespace Dezull\Bundle\HelpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class HelpTopicType extends AbstractType
{
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
                'width'                  => '400',
                'height'                 => '200',
                'language'               => 'en-uk',
                'filebrowser_image_upload_url'   => '/help/upload-image',
            ))
            ->add('category', 'entity', array(
                'class' => 'DezullHelpBundle:HelpCategory',
                'property' => 'name',
            ))
        ;
    }

    public function getName()
    {
        return 'dezull_helpbundle_helptopictype';
    }
}
