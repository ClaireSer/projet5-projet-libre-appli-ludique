<?php

namespace GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class QuestionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('question',      TextType::class)
        ->add('answers',   CollectionType::class, array(
            'entry_type'    => AnswerType::class,
            'allow_add'     => true,
            'allow_delete'  => true,
            'label'         => false
        ))
        ->add('difficulty',   ChoiceType::class, array(
            'label'         => 'Difficulté',
            'placeholder'   => '-- Choisissez la difficulté --',
            'choices'       => array(
                'Facile'        => 'facile',
                'Moyen'         => 'moyen',
                'Difficile'     => 'difficile'
            )
        ))
        ->add('subject',      SubjectType::class, array('label' => false))
        ->add('save',      SubmitType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GameBundle\Entity\Question'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'GameBundle_question';
    }
}
