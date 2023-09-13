<?php

namespace App\Form;

use App\Entity\Expenses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ExpensesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add(
                'title',
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
                    ],
                ]
            )
            ->add(
                'value',
                MoneyType::class,
                [
                    'label' => 'Valeur',
                    'required' => false,
                    'attr' => [
                        'class' => 'form_value',
                        'maxlength' => 8,
                        'placeholder' => 'Valeur',
                    ]
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Créer une dépense',
                    'attr' => [
                        'class' => 'btn btn-primary',
                    ],
                ]
            );

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expenses::class,
        ]);
    }
}
