<?php

namespace App\Form;

use App\Entity\KnowHowAnnualInterview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KnowHowAnnualInterviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wordingSkill', TextareaType::class, [
                'required' => false
            ])
            ->add('grade', IntegerType::class, [
                'required' => false
            ])
            ->add('collabComment', TextareaType::class, [
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
            'data_class' => KnowHowAnnualInterview::class,
        ]);
    }
}
