<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text')
            ->add('commentaire', 'textarea', ['required' => false])
            ->add('cle', 'integer')
            ->add('budget', 'entity', [
                'class' => 'Trez\LogicielTrezBundle\Entity\Budget',
                'property' => 'nom',
                'disabled' => true
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Trez\LogicielTrezBundle\Entity\Categorie'
        ));
    }

    public function getName()
    {
        return 'trez_logicieltrezbundle_categorietype';
    }
}
