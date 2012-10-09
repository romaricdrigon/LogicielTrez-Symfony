<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BudgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text')
            ->add('exercice', 'entity', [
                'class' => 'Trez\LogicielTrezBundle\Entity\Exercice',
                'property' => 'edition',
                'disabled' => true
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Trez\LogicielTrezBundle\Entity\Budget'
        ));
    }

    public function getName()
    {
        return 'trez_logicieltrezbundle_budgettype';
    }
}
