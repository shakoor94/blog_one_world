<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
// use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
// use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseignez ce champ.'
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 180,
                        'minMessage' => "Votre email est invalide. L'email doit comporter au minimum {{ limit }} caractères.",
                        'maxMessage' => "Votre email est invalide. L'email doit comporter au maximum {{ limit }} caractères.",
                    ]),
                    new Email([
                        'message' => 'Veuillez fournir un email valide.',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseignez ce champ.'
                    ]),
                    new Length([
                        'min' => 8,
                        'max' => 255,
                        'minMessage' => "Votre mot de passe est invalide. Le mot de passe doit comporter au minimum {{ limit }} caractères.",
                        'maxMessage' => "Votre mot de passe est invalide. Le mot de passe doit comporter au maximum {{ limit }} caractères."
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{4,255}$/',
                        'message' => 'Votre mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre, un caractère spécial .',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseignez ce champ.'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 30,
                        'minMessage' => "Votre prénom est invalide. Le prénom doit comporter au minimum {{ limit }} caractères.",
                        'maxMessage' => "Votre prénom est invalide. Le prénom doit comporter au maximum {{ limit }} caractères.",
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-z]+$/',
                        'message' => 'Votre prénom ne doit contenir que des lettres.',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseignez ce champ.'
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 100,
                        'minMessage' => "Votre nom est invalide. Le prénom doit comporter au minimum {{ limit }} caractères.",
                        'maxMessage' => "Votre nom est invalide. Le prénom doit comporter au maximum {{ limit }} caractères.",
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-z]+$/',
                        'message' => 'Votre nom ne doit contenir que des lettres.',
                    ]),
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseignez ce champ.'
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 30,
                        'minMessage' => "Votre pseudo est invalide. Le pseudo doit comporter au minimum {{ limit }} caractères.",
                        'maxMessage' => "Votre pseudo est invalide. Le pseudo doit comporter au maximum {{ limit }} caractères.",
                    ]),
                ],
            ])
            // ->add('recaptcha', EWZRecaptchaType::class, [
            //     'label' => false,
            //     'mapped' => false,
            //     'constraints' => [
            //         new RecaptchaTrue(),
            //     ],
            // ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto col-3 btn btn-primary'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['email'],
                    'message' => 'Cet email est déjà utilisé.',
                ]),
                new UniqueEntity([
                    'fields' => ['pseudo'],
                    'message' => 'Ce pseudo est déjà utilisé.',
                ]),
            ],
        ]);
    }
}
