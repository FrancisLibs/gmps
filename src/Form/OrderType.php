<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Account;
use App\Entity\Provider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation', TextType::class, [
                'label'     => 'Objet : ',
            ])
            ->add('expectedAmount', MoneyType::class, [
                'label'     =>  'Montant : ',
            ])
            ->add('expectedDeliveryDate', DateType::class, [
                'widget' => 'single_text',
                'label'     =>  'Date expédition : ',
            ])    
            ->add('provider', EntityType::class, [
                'class'     =>  Provider::class,
                'choice_label' => 'username',
                'label'     =>  'Fournisseur : ',
            ])   
            ->add('account', EntityType::class, [
                'class'     => Account::class,
                'choice_label'  =>  'designation',
                'label'         =>  'Compte : ',
                'multiple' => true,
                'expanded' => true,
            ])    
            ->add('Validation', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
