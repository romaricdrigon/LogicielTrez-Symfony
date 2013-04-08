<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DeclarationTvaDate extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date', array(
                    'input' => 'datetime',
                    'widget' => 'choice',
                    'days' => array(1)
                ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Trez\LogicielTrezBundle\Entity\DeclarationTva'
            ));
    }

    public function getName()
    {
        return 'trez_logicieltrezbundle_declarationtvadate';
    }
}
