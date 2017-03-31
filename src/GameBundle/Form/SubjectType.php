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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class SubjectType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $pattern = 'M%';

    $builder
      ->add('subject', EntityType::class, array(
          'class'         => 'GameBundle:Subject',
          'choice_label'  => 'subject',
          'label'         => 'Matière',
          'placeholder'   => '-- Choisissez une matière --'
      ))
      ->add('topic',      TopicType::class, array('label' => false))

      // ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
      //     $form = $event->getForm();
      //     $data = $event->getData();
      //     if (null === $data) {
      //         return;
      //     }
      //     $subject = $data->getSubject();
      //     if ($subject === 'Mathématiques') {
      //         $form->add('topic', TopicType::class, array('label' => false));
      //     }
      // })
      ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'GameBundle\Entity\Subject'
    ));
  }
}

