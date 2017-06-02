<?php
// src/GameBundle/Form/ThemeType.php

namespace GameBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ThemeType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('subject', SubjectType::class, array('label' => false))
      ->add('nameTopic', TextType::class, array(
        'label' => 'Sous-Matière',
        'attr'  => array(
          'placeholder' => 'Entrez une nouvelle sous-matière associée à la matière'
        )
      ))
      ->add('save',      SubmitType::class)
      ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'GameBundle\Entity\Topic'
    ));
  }
}

