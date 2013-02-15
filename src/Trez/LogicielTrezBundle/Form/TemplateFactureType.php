<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TemplateFactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('type', 'choice', array(
            'choices' => array(
                'FACTURE' => 'facture',
                'LETTRE' => 'lettre de paiement'
            )))
            ->add('contenu', 'textarea', array('required' => false))
            ->add('actif', 'checkbox', array(
            'required' => false
        ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Trez\LogicielTrezBundle\Entity\TemplateFacture'
        ));
    }

    public function getName()
    {
        return 'trez_logicieltrezbundle_templatefacturetype';
    }
}
