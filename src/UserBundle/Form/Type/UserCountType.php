<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use UserBundle\Form\Type\StringToArrayTransformer;


class UserCountType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new StringToArrayTransformer();

        $builder
        ->add('username',      TextType::class, array('label' => 'Nom de famille'))
        ->add('password',   RepeatedType::class, array(
            'type'            => PasswordType::class,
            'invalid_message' => 'Les mots de passe doivent être identiques.',
            'options'         => array('required' => true),
            'first_options'   => array('label' => 'Nouveau mot de passe'),
            'second_options'  => array('label' => 'Répétez le mot de passe'),
        ))
        ->add('roles',     ChoiceType::class, array(
            'choices'   => array(
                'Admin'         => 'ROLE_ADMIN',
                'Enseignant'    => 'ROLE_TEACHER',
                'Famille'       => 'ROLE_USER'
            ),
            'multiple'  => false,
            'expanded'  => true,
            'label'     => 'Rôle'
        ))
        ->add('save',      SubmitType::class)
        ;
        $builder->get('roles')->addModelTransformer($transformer);
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\UserCount'
        ));
    }
}
