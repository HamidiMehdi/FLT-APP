<?php

namespace App\Form\Workings\filter;

use App\Entity\User;
use App\Enum\WorkingEnum;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkingFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employees', EntityType::class, [
                'class' => User::class,
                'multiple' => false,
                'query_builder' => function (UserRepository $repository) {
                    return $repository->createQueryBuilder('u')
                        ->select('u');
                },
                'placeholder' => 'Tous'
            ])
            ->add('months', ChoiceType::class, [
                'choices' => $this->getLastTwleveMonths(),
                'placeholder' => 'Tous'
            ])
            ->add('locations', ChoiceType::class, [
                'choices' => $options['data']['em']->getRepository(User::class)->getAllLocations(),
                'placeholder' => 'Tous'
            ]);
    }

    private function getLastTwleveMonths()
    {
        $date = new \DateTime('now');
        $lastMonthsName = [];

        for ($i = 1; $i <= 12; $i++) {
            $lastMonthsName[WorkingEnum::getMonthsName($date->format('m')) . ' ' . $date->format('Y')] = $date->format('Y-m');
            $date->modify("-1 month");
        }

        return $lastMonthsName;
    }
}
