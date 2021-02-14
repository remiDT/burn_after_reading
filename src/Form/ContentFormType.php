<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'attr' => [
                    'rows' => 10
                ],
                'label' => 'Message'
            ])
            ->add('duration', ChoiceType::class, [
                'choices' => [
                    "7 days" => 2 * 24 * 3600,
                    "2 days" => 2 * 24 * 3600,
                    "1 day" => 24 * 3600,
                    "6 hours" => 6 * 3600,
                    "30 minutes" => 30 * 60,
                ],
                'label' => 'Retention period'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
