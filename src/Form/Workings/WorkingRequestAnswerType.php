<?php

namespace App\Form\Workings;

use App\Entity\Working;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkingRequestAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isAccepted', ChoiceType::class, [
                'choices' => [
                    'Accepter la demande.' => true,
                    'Refuser la demande.' => false
                ],
                'multiple' => false,
                'expanded' => true
            ])
            ->add('descriptionWorkingReject', TextareaType::class, [
                'required' => false
            ])
            ->add('reportRequest', CheckboxType::class, [
                'label'    => 'Le collaborateur doit faire un compte rendu.',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Working::class,
        ]);
    }
}