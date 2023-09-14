<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Tricounts;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TricountEditType extends AbstractType
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
                    'choice_value' => 'name',
                    'choice_label' => 'name',
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
                    'label' => 'Modifier Tricount',
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
