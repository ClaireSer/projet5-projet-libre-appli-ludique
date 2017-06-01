<?php

namespace UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;


class UserCountSettingsType extends UserCountType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('roles');
        $builder->remove('username');
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
        return 'userbundle_usercount_settings';
    }
}
