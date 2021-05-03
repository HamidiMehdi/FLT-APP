<?php

namespace App\Form;

use App\Entity\ProInterview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterviewProP2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('evolutionCurentPositionDesc', TextareaType::class, [
                'required' => false
            ])
            ->add('evolutionCurrentPositionExpectedTime', TextareaType::class, [
                'required' => false
            ])
            ->add('changePositionDesc', TextareaType::class, [
                'required' => false
            ])
            ->add('changePositionExpectedTime', TextareaType::class, [
                'required' => false
            ])
            ->add('skills', TextType::class, [
                'required' => false
            ])
            ->add('actionEnvisaged', TextType::class, [
                'required' => false
            ])
            ->add('formationWishes', CheckboxType::class, [
                'required' => false
            ])
            ->add('formationWishesType', TextareaType::class, [
                'required' => false
            ])
            ->add('formationWishesDesc', TextareaType::class, [
                'required' => false
            ])
            ->add('formationWishesExpectedTime', TextareaType::class, [
                'required' => false
            ])
            ->add('geographicMobility', CheckboxType::class, [
                'required' => false
            ])
            ->add('geographicMobilityLocation', TextareaType::class, [
                'required' => false
            ])
            ->add('geographicMobilityExpectedTime', TextareaType::class, [
                'required' => false
            ])
            ->add('groupMobilityLocation', TextareaType::class, [
                'required' => false
            ])
            ->add('groupMobilityExpectedTime', TextareaType::class, [
                'required' => false
            ])
            ->add('submit', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProInterview::class,
        ]);
    }
}
