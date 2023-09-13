<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Tricounts;
use App\Entity\User;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateTricountsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Title',
                    'attr' => [
                        'class' => 'form_title',
                        'maxlength' => 32,
                        'placeholder' => 'Title',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a title',
                        ]),
                        new NotBlank([
                            'message' => 'Please enter a title',
                        ])
                    ],
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'Description',
                    'required' => false,
                    'attr' => [
                        'class' => 'form_description',
                        'maxlength' => 54,
                        'placeholder' => 'Description',
                    ]
                ]
            )
            ->add('category',
                ChoiceType::class,
                [
                    'placeholder' => 'Choisissez une catégorie',
                    'choices' => [
                        new Category('sport'),
                        new Category('vacances'),
                        new Category('maison'),
                        new Category('autres'),
                    ],
                    'choice_value' => function (?Category $entity): string {
                        return $entity ? $entity->getName() : '';
                    },
                    'choice_label' => function (?Category $category): string {
                        return $category ? strtoupper($category->getName()) : '';
                    },
                    'choice_attr' => function (?Category $category): array {
                        return $category ? ['form_class' => 'category_' . strtolower($category->getName())] : [];
                    },
                ]
            )
            ->add('user', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'firstname',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'data-max' => 50,
                ],
            ])

            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Créer tricount',
                    'attr' => [
                        'class' => 'btn btn-primary',
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tricounts::class,
        ]);
    }
}
