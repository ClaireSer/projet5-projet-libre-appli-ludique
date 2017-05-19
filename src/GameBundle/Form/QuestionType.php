<?php

namespace GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;


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
            ->add('schoolClass', EntityType::class, array(
                'class'         => 'GameBundle:SchoolClass',
                'choice_label'  => 'schoolClass',
                'label'         => 'Niveau',
                'placeholder'   => '-- Choisissez le niveau --',
                'query_builder' => function (EntityRepository $er) {
                    return $er->orderBy();
                }
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
            ->add('topic', EntityType::class, array(
            'class'         => 'GameBundle:Topic',
            'choice_label'  => 'nameTopic',
            'group_by'      => 'subject.nameSubject',
            'label'         => 'Quel est le thème de votre question ?',
            'placeholder'   => '-- Choisissez une sous-matière --'
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
