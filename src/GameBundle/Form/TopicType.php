<?php
// src/GameBundle/Form/TopicType.php

namespace GameBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use GameBundle\Repository\TopicRepository;


class TopicType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('topic', EntityType::class, array(
          'class'         => 'GameBundle:Topic',
          'choice_label'  => 'topic',
          'label'         => 'Sous-matière',
          'placeholder'   => '-- Choisissez une sous-matière --'
      ));
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'GameBundle\Entity\Topic'
    ));
  }
}

