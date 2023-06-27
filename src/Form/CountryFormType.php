<?php

namespace App\Form;

use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CountryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('image', FileType::class, [
                'data_class' => null,
                 'required' => false,
                'label' => 'Image',
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
            ->add('submit', SubmitType::class, [
                'label' => ($options['country']) ? "Modifier " . $options['country']->getTitle() : "Ajouter",
                'attr' => ['class' => '']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
            'country' => null,
        ]);
    }
}
