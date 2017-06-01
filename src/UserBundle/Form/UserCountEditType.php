<?php

namespace UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;


class UserCountEditType extends UserCountType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('password');
    }

    public function getParent()
    {
        return UserCountType::class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'userbundle_usercount_edit';
    }
}
