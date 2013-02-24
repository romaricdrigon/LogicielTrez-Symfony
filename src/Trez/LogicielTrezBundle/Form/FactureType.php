<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', 'integer', array(
                'attr' => array('min' => 1)
            ))
            ->add('objet', 'text')
            ->add('montant', 'money')
            ->add('date', 'date', array(
                'input' => 'datetime',
                'widget' => 'single_text'
            ))
            ->add('date_paiement', 'date', array(
                'input' => 'datetime',
                'widget' => 'single_text',
                'required' => false
            ))
            ->add('commentaire', 'textarea', array('required' => false))
            ->add('ref_paiement', 'text', array('required' => false))
            ->add('ligne', 'entity', array(
                'class' => 'Trez\LogicielTrezBundle\Entity\Ligne',
                'property' => 'nom',
                'disabled' => true
            ))
            ->add('tiers', 'entity', array(
                'class' => 'Trez\LogicielTrezBundle\Entity\Tiers',
                'property' => 'nom',
                'empty_value' => ''
            ))
            ->add('methodePaiement', 'entity', array(
                'class' => 'Trez\LogicielTrezBundle\Entity\MethodePaiement',
                'property' => 'nom',
                'empty_value' => 'Choisissez...',
                'required' => false
            ))
            ->add('typeFacture', 'entity', array(
                'class' => 'Trez\LogicielTrezBundle\Entity\TypeFacture',
                'property' => 'abr'
            ))
            ->add('tvas', 'collection', array(
                'type' => new TvaType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'error_bubbling' => false
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Trez\LogicielTrezBundle\Entity\Facture',
            'validation_groups' => array('under_total', 'Default')
        ));
    }

    public function getName()
    {
        return 'trez_logicieltrezbundle_facturetype';
    }
}
