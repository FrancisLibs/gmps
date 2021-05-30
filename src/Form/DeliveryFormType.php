<?php

namespace App\Form;

use App\Entity\DeliveryForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DeliveryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('deliveryFormNumber', TextType::class, [
                'label'     =>  'Numéro : ',
            ])
            ->add('deliveryFormDate', DateType::class, [
                'widget' => 'single_text',
                'label'     =>  'Date : ',
            ])
            ->add('validation', SubmitType::class, [
                'label' => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeliveryForm::class,
        ]);
    }
}
