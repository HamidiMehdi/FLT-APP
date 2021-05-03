<?php

namespace App\Form;

use App\Entity\EvaluationFormationAnnualInterview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationFormationAnnualInterviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('formation', TextareaType::class, [
                'required' => false
            ])
            ->add('employeeAppreciation', TextareaType::class, [
                'required' => false
            ])
            ->add('employeeComment', TextareaType::class, [
                'required' => false
            ])
            ->add('managerAppreciation', TextareaType::class, [
                'required' => false
            ])
            ->add('managerComment', TextareaType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EvaluationFormationAnnualInterview::class,
        ]);
    }
}
