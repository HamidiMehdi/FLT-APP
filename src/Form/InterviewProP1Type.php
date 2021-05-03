<?php

namespace App\Form;

use App\Entity\ProInterview;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

class InterviewProP1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('jobTitle', TextType::class, [
                'required' => false
            ])
            ->add('currentFunction', TextType::class, [
                'required' => false
            ])
            ->add('functionSeniority', TextType::class, [
                'required' => false
            ])
            ->add('affectation', ChoiceType::class, [
                'choices' => ProInterview::getAffectionChoice(),
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'required' => false,
                'mapped' => false
            ])
            ->add('dateEntered', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'required' => false,
                'mapped' => false
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProInterview::class,
        ]);
    }
}
