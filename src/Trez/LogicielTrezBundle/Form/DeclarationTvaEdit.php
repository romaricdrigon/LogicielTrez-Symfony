<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DeclarationTvaEdit extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date', array(
                    'input' => 'datetime',
                    'widget' => 'single_text',
                    'read_only' => true
                ))
            ->add('solde_precedent', 'money', array(
                    'precision' => 0
                ))
            ->add('solde_final', 'money', array(
                    'precision' => 0,
                    'read_only' => true
                ))
            ->add('commentaire', 'textarea', array(
                    'required' => false
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
        return 'trez_logicieltrezbundle_declarationtvaedit';
    }
}
