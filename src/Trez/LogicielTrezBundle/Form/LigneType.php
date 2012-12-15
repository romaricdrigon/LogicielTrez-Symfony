<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LigneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text')
            ->add('commentaire', 'textarea', array('required' => false))
            ->add('cle', 'integer')
            ->add('debit', 'money')
            ->add('credit', 'money')
            ->add('sousCategorie', 'entity', array(
                'class' => 'Trez\LogicielTrezBundle\Entity\SousCategorie',
                'property' => 'nom',
                'disabled' => true
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Trez\LogicielTrezBundle\Entity\Ligne',
            'validation_groups' => array('under_total', 'Default')
        ));
    }

    public function getName()
    {
        return 'trez_logicieltrezbundle_lignetype';
    }
}
