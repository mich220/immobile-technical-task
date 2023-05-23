<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Product\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('price', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('discount_price', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('discount_period_starts_at', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'input_format' => 'Y-m-d H:i:s',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('discount_period_ends_at', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'input_format' => 'Y-m-d H:i:s',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create Product',
                'attr' => [
                    'class' => 'btn btn-primary mt-3',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_protection' => false,
        ]);
    }
}
