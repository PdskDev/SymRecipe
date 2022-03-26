<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'attr'=> [
                    'class' => 'form-control mt-3',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'label' => 'Nom/Prénom',
                'label_attr' => ['class' => 'form-label mt-3'
            ],
        'constraints' => [
            new Assert\Length(['min'=>'2', 'max'=>'50']),
            new Assert\NotBlank()
        ]
        ])
            ->add('pseudo', TextType::class, [
                'attr'=> [
                    'class' => 'form-control mt-3',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'required' => false,
                'label' => 'Pseudo (facultatif)',
                'label_attr' => ['class' => 'form-label mt-3'
            ],
        'constraints' => [
            new Assert\Length(['min'=>'2', 'max'=>'50']),
        ]
        ])
            ->add('email', EmailType::class, [
                'attr'=> [
                    'class' => 'form-control mt-3',
                    'minlength' => '10',
                    'maxlength' => '180',
                ],
                'label' => 'Adresse Email',
                'label_attr' => ['class' => 'form-label mt-3'
            ],
        'constraints' => [
            new Assert\Email(),
            new Assert\Length(['min'=>'10', 'max'=>'180']),
            new Assert\NotBlank(),
        ]
        ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'label_attr' => ['class' => 'form-label mt-3'
                ],
                    'attr'=> [
                        'class' => 'form-control mt-3',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer votre mot de passe',
                    'label_attr' => ['class' => 'form-label mt-3'
                ],
                'attr'=> [
                    'class' => 'form-control mt-3',
                ],
                ],
                'invalid_message' => "Les mot de passe ne correspondent pas"

            ],
            [
                'attr'=> [
                    'class' => 'form-control',
                    'minlength' => '10',
                    'maxlength' => '180',
                ],
                'label' => 'Mot de passe',
                'label_attr' => ['class' => 'form-label mt-3'
            ],
        'constraints' => [
            new Assert\Length(['min'=>'10', 'max'=>'180']),
            new Assert\NotBlank()
        ]
        ])
            ->add('submit', SubmitType::class , [
                'attr' => ['class' => 'btn btn-primary mt-3'],
                'label' => 'S\'enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
