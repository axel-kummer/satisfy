<?php


namespace Playbloom\Satisfy\Form\Type;


use Playbloom\Satisfy\Model\OAuth;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class OAuthType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => [
                        'Please choose .....' => '',
                        'GitLab' => 'gitlab',
                        'github' => 'github',
                        'Bitbucket' => 'bitbucket',
                    ],
                    'required' => true,
                    'empty_data' => '',
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                    'attr' => [
                        'placeholder' => 'Type',
                        'class' => 'col col-lg-2'
                    ],
                ]
            )->add(
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
                'token',
                TextType::class,
                [
                    'required' => true,
                    'empty_data' => '',
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                    'attr' => [
                        'placeholder' => 'OAuth-Token',
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OAuth::class,
            'empty_data' => new OAuth(),
        ]);
    }

    public function getBlockPrefix()
    {
        return 'OAuthType';
    }
}
