<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Account;
use App\Entity\Provider;
use App\Data\SearchOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SearchOrderForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', IntegerType::class, [
                'label'     => false,
                'required'  => false,
                'attr'      => ['placeholder' => 'Num de commande']
            ])

            ->add('provider', EntityType::class, [
                'label'     => false,
                'required'  => false,
                'class'     => Provider::class,
                'placeholder' => 'Fournisseur...'
            ])

            ->add('user', EntityType::class, [
                'label'     => false,
                'required'  => false,
                'class'     => User::class,
                'placeholder' => 'Utilisateur...'
            ])
            ->add('account', EntityType::class, [
                'label'     => false,
                'required'  => false,
                'class'     => Account::class,
                'expanded'  => true,
                'multiple'  => true,
            ])

            ->add('status', ChoiceType::class, [
                'label' => false,
                'choices'  => [
                    'Encours' => Order::EN_COURS,
                    'En attente' => Order::EN_ATTENTE,
                    'Cloturée' => Order::CLOTUREE
                ],
                'expanded'  => true,
                'multiple'  => true,
            ])

            ->add('designation', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Désignation...'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchOrder::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}