<?php

namespace App\Form;

use App\Entity\AnnualInterview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterviewAnnualP6Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employeeOpinion',  TextareaType::class, [
                'required' => false
            ])
            ->add('employeeSignature', HiddenType::class, [
                'required' => false
            ])
        ;

        if ($options['in_progress'] === true) {
            $builder
                ->add('refuseSignature', CheckboxType::class, [
                    'required' => false
                ])
                ->add('managerOpigion', TextareaType::class, [
                    'required' => false
                ])
                ->add('managerSignature', HiddenType::class, [
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
