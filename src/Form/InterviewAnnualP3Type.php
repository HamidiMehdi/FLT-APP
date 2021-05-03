<?php

namespace App\Form;

use App\Entity\AnnualInterview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterviewAnnualP3Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('knowHows', CollectionType::class, [
                'entry_type' => KnowHowAnnualInterviewType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'by_reference' => false
            ])
            ->add('knowHowAvg', IntegerType::class, [
                'required' => false
            ])
            ->add('knowMakes', CollectionType::class, [
                'entry_type' => KnowMakeAnnualInterviewType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'by_reference' => false
            ])
            ->add('knowMakeAvg', IntegerType::class, [
                'required' => false
            ])
            ->add('submit', SubmitType::class);
        ;

        if ($options['in_progress'] === true) {
            $builder
                ->add('knowMakeComment', TextareaType::class, [
                    'required' => false
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnualInterview::class,
            'in_progress' => false
        ]);
    }
}
