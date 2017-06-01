<?php

namespace UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;


class UserCountSignupType extends UserCountType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('roles');
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
        return 'userbundle_usercount_signup';
    }
}
