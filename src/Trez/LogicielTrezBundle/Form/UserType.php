<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Les deux mots de passe doivent correspondre',
                'first_options'  => array('label' => 'Mot de passe :'),
                'second_options' => array('label' => 'Répétez le mot de passe :'),
            ))
            ->add('mail', 'email')
            ->add('type', 'choice', array(
                'choices' => array(
                    'ROLE_USER' => 'Lecteur',
                    'ROLE_ADMIN' => 'Administrateur',
                    'DISABLED' => 'Désactivé'
                )
            ))
            ->add('categories', 'entity', array(
                'class' => 'TrezLogicielTrezBundle:Categorie',
                'multiple' => true,
                'required' => false,
                'group_by' => 'budget.full_nom'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Trez\LogicielTrezBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'trez_logicieltrezbundle_usertype';
    }
}
