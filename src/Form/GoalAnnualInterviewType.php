<?php

namespace App\Form;

use App\Entity\GoalAnnualInterview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoalAnnualInterviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wordingGoal', TextareaType::class, [
                'required' => false
            ])
            ->add('goalAchieve', TextareaType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GoalAnnualInterview::class,
        ]);
    }
}
