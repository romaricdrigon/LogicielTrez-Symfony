<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExerciceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('edition')
            ->add('annee_1', 'date', array(
                'widget' => 'single_text',
            ))
            ->add('annee_2', 'date', array(
                'widget' => 'single_text',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Trez\LogicielTrezBundle\Entity\Exercice'
        ));
    }

    public function getName()
    {
        return 'trez_logicieltrezbundle_exercicetype';
    }
}
