<?php
// src/GameBundle/Form/TopicType.php

namespace GameBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TopicType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('subject', EntityType::class, array(
          'class'         => 'GameBundle:Subject',
          'choice_label'  => 'nameSubject',
          'label'         => 'Matière',
          'placeholder'   => '-- Choisissez une matière --'
      ))
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

