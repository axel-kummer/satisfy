<?php


namespace Playbloom\Satisfy\Form\Type;

use Playbloom\Satisfy\Model\BasicAuth;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class BasicAuthType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'domain',
                TextType::class,
                [
                    'required' => true,
                    'empty_data' => '',
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                    'attr' => [
                        'placeholder' => 'Domain',
                    ],
                ]
            )->add(
                'username',
                TextType::class,
                [
                    'required' => true,
                    'empty_data' => '',
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                    'attr' => [
                        'placeholder' => 'Username',
                    ],
                ]
            )->add(
                'password',
                TextType::class,
                [
                    'required' => true,
                    'empty_data' => '',
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                    'attr' => [
                        'placeholder' => 'Password',
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BasicAuth::class,
            'empty_data' => new BasicAuth(),
        ]);
    }

    public function getBlockPrefix()
    {
        return 'BasicAuthType';
    }
}
