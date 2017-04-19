<?php
// src/GameBundle/Form/SubjectType.php

namespace GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
      ->add('nameSubject', TextType::class, array(
        'label' => 'Matière', 
        'attr'  => array(
          'placeholder' => 'Entrez une nouvelle matière'
        )
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

