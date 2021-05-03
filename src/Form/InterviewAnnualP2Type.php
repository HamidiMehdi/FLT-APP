<?php

namespace App\Form;

use App\Entity\AnnualInterview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterviewAnnualP2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bilans', CollectionType::class, [
                'entry_type' => BilanAnnualInterviewType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'by_reference' => false
            ])
            ->add('bilanAvg', IntegerType::class, [
                'required' => false
            ])
            ->add('commentCollabWorkingEnv', TextareaType::class, [
                'required' => false
            ])
            ->add('commentCollabImprovement', TextareaType::class, [
                'required' => false
            ])
            ->add('submit', SubmitType::class);
        ;

        if ($options['in_progress'] === true) {
            $builder
                ->add('commentManagerWorkingEnv', TextareaType::class, [
                    'required' => false
                ])
                ->add('commentManagerStrongPoints', TextareaType::class, [
                    'required' => false
                ])
                ->add('commentManagerImprovement', TextareaType::class, [
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
