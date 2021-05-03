<?php

namespace App\Form;

use App\Entity\ProInterview;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterviewProP3Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        $builder
            ->add('employeeOpinion', TextareaType::class, [
                'required' => false
            ])
            ->add('employeeSignature', HiddenType::class, [
                'required' => false
            ]);

        if ($options['in_progress'] === true) {
            $builder
                ->add('managerOpinion', TextareaType::class, [
                    'required' => false
                ])
                ->add('managerSignature', HiddenType::class, [
                    'required' => false
                ])
                ->add('secondManagerSignature', HiddenType::class, [
                    'required' => false
                ])
                ->add('secondManage', EntityType::class, [
                    'class' => User::class,
                    'multiple' => false,
                    'query_builder' => function (UserRepository $repository) use ($entity) {
                        $usersManager = $repository->getManagersEntityByUser($entity->getEmployee());
                        return $repository->createQueryBuilder('u')
                            ->select('u')
                            ->where('u.id IN (:usersManager)')
                            ->setParameter('usersManager', $usersManager);
                    }
                ])
                ->add('acceptSecondManager', CheckboxType::class, [
                    'required' => false
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProInterview::class,
            'in_progress' => false
        ]);
    }
}
