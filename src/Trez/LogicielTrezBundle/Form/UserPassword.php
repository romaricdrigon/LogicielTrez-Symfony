<?php

namespace Trez\LogicielTrezBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserPassword extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'disabled' => true
            ))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Les deux mots de passe doivent correspondre',
                'first_options'  => array('label' => 'Mot de passe :'),
                'second_options' => array('label' => 'Répétez le mot de passe :'),
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
        return 'trez_logicieltrezbundle_userpassword';
    }
}
