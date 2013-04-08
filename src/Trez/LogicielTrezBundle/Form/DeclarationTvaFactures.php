<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Trez\LogicielTrezBundle\Entity\Exercice;

class DeclarationTvaFactures extends AbstractType
{
    private $date_debut;
    private $date_fin;

    public function __construct(\DateTime $date_debut, \DateTime $date_fin)
    {
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date_debut = $this->date_debut;
        $date_fin = $this->date_fin;

        $builder
            ->add('factures', 'entity', array(
                    'class' => 'TrezLogicielTrezBundle:Facture',
                    'property' => 'numero',
                    'multiple' => true,
                    'required' => false,
                    'expanded' => true,
                    'query_builder' => function(EntityRepository $er) use (
                        $date_debut,
                        $date_fin
                    ) {
                        return $er->createQueryBuilder('f')
                            ->leftJoin('f.ligne', 'l')
                            ->where('f.date_paiement >= ?1')
                            ->andWhere('f.date_paiement < ?2')
                            ->orderBy('f.numero', 'ASC')
                            ->setParameters(array(
                                    1 => $date_debut,
                                    2 => $date_fin
                                ));
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
