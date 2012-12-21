<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TvaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant_ht', 'money')
            ->add('montant_tva', 'money')
            // ->add('facture') // not used here
            ->add('classeTva', 'entity', array(
                'class' => 'Trez\LogicielTrezBundle\Entity\ClasseTva',
                'property' => 'nom'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Trez\LogicielTrezBundle\Entity\Tva'
        ));
    }

    public function getName()
    {
        return 'trez_logicieltrezbundle_tvatype';
    }
}
