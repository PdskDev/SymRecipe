<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'label_attr' => ['class' => 'form-label mt-3'
                ],
                    'attr'=> [
                        'class' => 'form-control mt-3',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer nouveau mot de passe',
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
                'label' => 'Nouveau mot de passe',
                'label_attr' => ['class' => 'form-label mt-3'
            ],
        'constraints' => [
            new Assert\Length(['min'=>'5', 'max'=>'180']),
            new Assert\NotBlank()
        ]
        ])

        ->add('oldPassword', PasswordType::class, [
            'attr'=> [
                'class' => 'form-control mt-3',
                'minlength' => '2',
                'maxlength' => '50',
            ],
            'required' => true,
            'label' => 'Mot de passe actuel',
            'label_attr' => ['class' => 'form-label mt-3'
        ],
    'constraints' => [
        new Assert\Length(['min'=>'5', 'max'=>'50']),
        new Assert\NotBlank()
    ]
    ])
            ->add('submit', SubmitType::class , [
                'attr' => ['class' => 'btn btn-primary mt-3'],
                'label' => 'Modifier votre mot de passe'
            ])
        ;
    } 
}
