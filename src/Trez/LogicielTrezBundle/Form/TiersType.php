<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TiersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('telephone')
            ->add('mail')
            ->add('fax')
            ->add('adresse')
            ->add('responsable')
            ->add('rib')
            ->add('ordre_cheque')
            ->add('commentaire')
            ->add('etranger', 'checkbox', array(
                'required' => false
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Trez\LogicielTrezBundle\Entity\Tiers'
        ));
    }

    public function getName()
    {
        return 'trez_logicieltrezbundle_tierstype';
    }
}
