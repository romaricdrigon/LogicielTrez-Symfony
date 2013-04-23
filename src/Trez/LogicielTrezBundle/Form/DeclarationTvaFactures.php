<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Trez\LogicielTrezBundle\Entity\Exercice;

class DeclarationTvaFactures extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('factures', 'entity', array(
                    'class' => 'TrezLogicielTrezBundle:Facture',
                    'property' => 'numero',
                    'multiple' => true,
                    'required' => false,
                    'expanded' => true,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('f')
                            ->leftJoin('f.ligne', 'l')
                            ->where('f.declarationTva is null')
                            ->orderBy('f.numero', 'ASC');
                    }
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Trez\LogicielTrezBundle\Entity\DeclarationTva'
            ));
    }

    public function getName()
    {
        return 'trez_logicieltrezbundle_declarationtvafactures';
    }
}
