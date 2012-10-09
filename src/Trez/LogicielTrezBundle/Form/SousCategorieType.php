<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SousCategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text')
            ->add('commentaire', 'textarea', ['required' => false])
            ->add('cle', 'integer')
            ->add('categorie', 'entity', [
                'class' => 'Trez\LogicielTrezBundle\Entity\Categorie',
                'property' => 'nom',
                'disabled' => true
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Trez\LogicielTrezBundle\Entity\SousCategorie'
        ));
    }

    public function getName()
    {
        return 'trez_logicieltrezbundle_souscategorietype';
    }
}
