<?php

namespace App\Form\Workings;

use App\Entity\Working;
use App\Enum\WorkingEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewWorkingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startAt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false
            ])
            ->add('periodStartAt', ChoiceType::class, [
                'choices' => WorkingEnum::getChoiceDate(),
                'empty_data' => null,
                'placeholder' => null
            ])
            ->add('endAt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false
            ])
            ->add('periodEndAt', ChoiceType::class, [
                'choices' => WorkingEnum::getChoiceDate(),
                'empty_data' => null,
                'placeholder' => null
            ])
            ->add('description', TextareaType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Working::class,
        ]);
    }
}
