<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        ->add('right_answer',   TextType::class)
        ->add('wrong_answer1',   TextType::class)
        ->add('wrong_answer2',   TextType::class)
        ->add('wrong_answer3',   TextType::class)
        ->add('difficulty',   ChoiceType::class, array(
            'choices'  => array(
                'Facile'        => 'facile',
                'Moyen'         => 'moyen',
                'Difficile'     => 'difficile'
            )
        ))
        ->add('subject',      ChoiceType::class)
        ->add('topic',      ChoiceType::class)
        
        ->add('connexion',      SubmitType::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Question'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'UserBundle_question';
    }
}
