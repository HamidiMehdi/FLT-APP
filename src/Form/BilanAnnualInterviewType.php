<?php

namespace App\Form;

use App\Entity\BilanAnnualInterview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilanAnnualInterviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('achievement', TextareaType::class, [
                'required' => false
            ])
            ->add('grade', IntegerType::class, [
                'required' => false
            ])
            ->add('commentCollab', TextareaType::class, [
                'required' => false
            ])
            ->add('commentManager', TextareaType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BilanAnnualInterview::class,
        ]);
    }
}
