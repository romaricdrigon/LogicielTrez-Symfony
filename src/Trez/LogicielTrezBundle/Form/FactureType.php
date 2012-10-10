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
            ->add('numero', 'integer')
            ->add('objet', 'text')
            ->add('montant', 'money')
            ->add('date', 'date', [
                'input' => 'datetime',
                'widget' => 'choice',
                'format' => 'dd / MM / yyyy',
                'pattern' => '{{ day }} / {{ month }} / {{ year }}',
                'data' => new \DateTime()
            ])
            ->add('date_paiement', 'date', [
                'input' => 'datetime',
                'widget' => 'choice',
                'format' => 'dd / MM / yyyy',
                'pattern' => '{{ day }} / {{ month }} / {{ year }}',
                'required' => false
            ])
            ->add('commentaire', 'textarea', ['required' => false])
            ->add('ref_paiement', 'text', ['required' => false])
            ->add('ligne', 'entity', [
                'class' => 'Trez\LogicielTrezBundle\Entity\Ligne',
                'property' => 'nom',
                'disabled' => true
            ])
            ->add('tiers', 'entity', [
                'class' => 'Trez\LogicielTrezBundle\Entity\Tiers',
                'property' => 'nom'
            ])
            ->add('methodePaiement', 'entity', [
                'class' => 'Trez\LogicielTrezBundle\Entity\MethodePaiement',
                'property' => 'nom',
                'empty_value' => 'Choisissez...',
                'required' => false
            ])
            ->add('typeFacture', 'entity', [
                'class' => 'Trez\LogicielTrezBundle\Entity\TypeFacture',
                'property' => 'abr'
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Trez\LogicielTrezBundle\Entity\Facture'
        ));
    }

    public function getName()
    {
        return 'trez_logicieltrezbundle_facturetype';
    }
}
