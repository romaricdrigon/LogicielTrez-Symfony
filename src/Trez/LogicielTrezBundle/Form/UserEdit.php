<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserEdit extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('mail', 'email')
            ->add('type', 'choice', array(
                'choices' => array(
                    'ROLE_USER' => 'Lecteur',
                    'ROLE_ADMIN' => 'Administrateur',
                    'DISABLED' => 'Désactivé'
                )
            ))
            ->add('lignes', 'entity', array(
                'class' => 'TrezLogicielTrezBundle:Ligne',
                'multiple' => true,
                'required' => false,
                'expanded' => true
            ))
            ->add('can_openid', 'checkbox', array(
                'required' => false
            ))
            ->add('can_credentials', 'checkbox', array(
                'required' => false
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
        return 'trez_logicieltrezbundle_useredit';
    }
}
