<?php

namespace App\Form;

use App\Entity\Expenses;
use App\Entity\Tricounts;
use App\Entity\Users;
use App\Service\GetUserByExpenses;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditExpensesType extends AbstractType
{
    private $getUserByExpenses;

    public function __construct(GetUserByExpenses $getUserByExpenses)
    {
        $this->getUserByExpenses = $getUserByExpenses;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('value', MoneyType::class, [
                'label' => 'Valeur',
                'currency' => 'EUR',
            ])
            ->add('concerned_users', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'firstname',
                'multiple' => true, // Allow multiple selection
                'expanded' => true, // If you want checkboxes instead of a select box
                'by_reference' => false,

            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expenses::class,
        ]);
    }
}
