<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use GameBundle\Repository\SchoolClassRepository;


class GamerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username',      TextType::class, array('label' => 'Pseudo'))
        ->add('firstname',      TextType::class, array('label' => 'Prénom'))
        ->add('role',     ChoiceType::class, array(
            'choices'   => array(
                'Élève'     => 'Élève',
                'Parent'    => 'Parent',
                'Autre'     => 'Autre',
            ),
            'label'     => 'Vous êtes :',
            'placeholder'   => '-- Choisissez une catégorie --'            
        ))
        ->add('schoolClass',      EntityType::class, array(
            'class'         => 'GameBundle:schoolClass',
            'choice_label'  => 'schoolClass',
            'placeholder'   => '-- Choisissez un niveau --',
            'label'         => 'Choisissez votre niveau',
            'query_builder' => function(SchoolClassRepository $er) {
                return $er->orderBy();
            }
        ))        
        ->add('save',      SubmitType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Gamer'
        ));
    }
}
