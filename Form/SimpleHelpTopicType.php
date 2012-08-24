<?php

namespace Dezull\Bundle\HelpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SimpleHelpTopicType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('category', 'entity', array(
                'class' => 'DezullHelpBundle:HelpCategory',
                'property' => 'name',
            ))
        ;
    }

    public function getName()
    {
        return 'dezull_examplebundle_topictype';
    }
}
