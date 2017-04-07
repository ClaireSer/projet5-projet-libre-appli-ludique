<?php
// src/GameBundle/Form/SubjectType.php

namespace GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use GameBundle\Repository\SubjectRepository;
use GameBundle\Repository\TopicRepository;


class SubjectType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('subject', EntityType::class, array(
          'class'         => 'GameBundle:Subject',
          'choice_label'  => 'subject',
          'label'         => 'Matière',
          'placeholder'   => '-- Choisissez une matière --'
      ))
      ->add('topic', EntityType::class, array(
          'class'         => 'GameBundle:Topic',
          'choice_label'  => 'topic',
          'label'         => 'Sous-matière',
          'placeholder'   => '-- Choisissez une sous-matière --'
      ))
      ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'GameBundle\Entity\Subject'
    ));
  }
}

