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
            ->add('numero')
            ->add('objet')
            ->add('montant')
            ->add('date')
            ->add('date_paiement')
            ->add('commentaire')
            ->add('ref_paiement')
            ->add('ligne')
            ->add('tiers')
            ->add('methodePaiement')
            ->add('typeFacture')
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
