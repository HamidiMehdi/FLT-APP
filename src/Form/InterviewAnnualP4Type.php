<?php

namespace App\Form;

use App\Entity\AnnualInterview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterviewAnnualP4Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('formationAnnualInterview', CollectionType::class, [
                'entry_type' => FormationAnnualInterviewType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'by_reference' => false
            ])
            ->add('evaluationFormationAnnualInterviews', CollectionType::class, [
                'entry_type' => EvaluationFormationAnnualInterviewType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'by_reference' => false
            ])
            ->add('formationDesiredAnnualInterviews', CollectionType::class, [
                'entry_type' => FormationDesiredAnnualInterviewType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'by_reference' => false
            ])
            ->add('submit', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnualInterview::class,
        ]);
    }
}
