<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BlobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Fichier', FileType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '1Mi',
                    ])
                ]
            ])
            ->add('duration', ChoiceType::class, [
                'choices' => [
                    "2 jours" => 2 * 24 * 3600,
                    "1 jour" => 24 * 3600,
                    "6 heures" => 6 * 3600,
                    "30 minutes" => 30 * 60,
                ],
                'label' => 'Durée de conservation'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
