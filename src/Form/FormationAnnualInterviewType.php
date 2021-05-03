<?php

namespace App\Form;

use App\Entity\FormationAnnualInterview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationAnnualInterviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', TextType::class, [
                'required' => false
            ])
            ->add('formation', TextType::class, [
                'required' => false
            ])
            ->add('start', TextType::class, [
                'required' => false
            ])
            ->add('end', TextType::class, [
                'required' => false
            ])
            ->add('duration', TextType::class, [
                'required' => false
            ])
            ->add('organisme', TextType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormationAnnualInterview::class,
        ]);
    }
}
