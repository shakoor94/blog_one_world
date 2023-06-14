<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'article"
            ])
            ->add('content', TextareaType::class, [
                'label' => "Description"
            ])
            ->add('image', FileType::class, [
                'data_class' => null,
                'label' => 'Image',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG, JPG ou PNG.',
                    ]),
                    new Image([
                        'minWidth' => 200,
                        'minHeight' => 200,
                        'minWidthMessage' => 'La largeur de l\'image doit être d\'au moins 200 pixels.',
                        'minHeightMessage' => 'La hauteur de l\'image doit être d\'au moins 200 pixels.',
                    ]),
                ],
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'label' => "Pays",
                'choice_label' => 'title',
                'placeholder' => 'Sélectionnez un pays',
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Créer",
                'attr' => [
                    'class' => ''
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
