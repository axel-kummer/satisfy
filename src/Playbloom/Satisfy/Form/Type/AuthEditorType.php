<?php


namespace Playbloom\Satisfy\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class AuthEditorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'oauth',
            CollectionType::class, [
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'entry_type' => OAuthType::class,
                'prototype' => true,
                'attr' => [
                    'class' => 'collection_auth'
                ]
            ]
        )->add(
            'basicauth',
            CollectionType::class, [
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'entry_type' => BasicAuthType::class,
                'prototype' => true,
                'attr' => [
                    'class' => 'collection_auth'
                ]
            ]
        );


    }
}
