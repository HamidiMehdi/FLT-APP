<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Working;
use App\Enum\EmailEnum;
use App\Enum\WorkingEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\NamedAddress;
use Twig\Environment;

class WorkingService
{
    /**  @var EntityManagerInterface */
    private $em;

    /** @var MailerInterface */
    private $mailer;

    /** @var Environment */
    private $templating;

    /**
     * WorkingService constructor.
     * @param EntityManagerInterface $em
     * @param MailerInterface $mailer
     * @param Environment $templating
     */
    public function __construct(EntityManagerInterface $em, MailerInterface $mailer, Environment $templating)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * Envoi email au manager pour l'informer de la demande
     * @param Working $working
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendNewWorkingRequest(Working $working)
    {
        foreach ($working->getEmployee()->getManager() as $manager) {
            $manager = $manager->getManager();

            $email = (new Email())
                ->from(new NamedAddress(EmailEnum::FROM, EmailEnum::LABEL_FROM))
                ->to($manager->getEmail())
                ->cc(EmailEnum::CC)
                ->subject('Demande de télétravail')
                ->html($this->templating->render('workings/email/new_working_request.html.twig', [
                    'working' => $working,
                    'manager' => $manager
                ])
                );

            $this->mailer->send($email);
        }
    }

    /**
     * Envoi email pour avertir le collaborateur du choix de sa demande de travail
     * @param Working $working
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendChoiceWorkingRequest(Working $working)
    {
        $subject = 'Retour sur votre demande de télétravail du ' . $working->getStartAt()->format('d/m/Y') . ' au ' . $working->getEndAt()->format('d/m/Y');
        $email = (new Email())
            ->from(new NamedAddress(EmailEnum::FROM, EmailEnum::LABEL_FROM))
            ->to($working->getEmployee()->getEmail())
            ->cc(EmailEnum::CC)
            ->subject($subject)
            ->html($this->templating->render('workings/email/choice_working_request.html.twig', [
                'working' => $working
            ]));

        $this->mailer->send($email);
    }

    /**
     * Envoi email au manager pour l'informer du compte rendu
     * @param Working $working
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendReportWorking(Working $working)
    {
        foreach ($working->getEmployee()->getManager() as $manager) {
            $manager = $manager->getManager();

            $email = (new Email())
                ->from(new NamedAddress(EmailEnum::FROM, EmailEnum::LABEL_FROM))
                ->to($working->getEmployee()->getEmail())
                ->cc(EmailEnum::CC)
                ->subject('Compte rendu du collaborateur ' . $working->getEmployee()->getFullName())
                ->html($this->templating->render('workings/email/report_working.html.twig', [
                    'working' => $working,
                    'manager' => $manager
                ]));

            $this->mailer->send($email);
        }
    }

    /**
     * @param \DateTime $date
     * @return array
     */
    public function getWorkingDescriptionDay(\DateTime $date)
    {
        $userCounterPerLocation = $this->em->getRepository(User::class)->getUserCounterPerLocation();
        $locationsCounter = $this->em->getRepository(Working::class)->getWorkingsCounterPerLocationByDay($date);
        $workings = $this->em->getRepository(Working::class)->getWorkingsByDay($date);

        $workingsSplit = [
            WorkingEnum::DAY => [],
            WorkingEnum::MORNING => [],
            WorkingEnum::AFTERNOON => []
        ];

        foreach ($workings as $working) {

            if ($working->getStartAt() == $working->getEndAt()) {
                if ($working->getPeriodStartAt() === $working->getPeriodEndAt()) {
                    if ($working->getPeriodStartAt() === WorkingEnum::MORNING) {
                        $workingsSplit[WorkingEnum::MORNING][] = $working;
                        continue;
                    }
                    $workingsSplit[WorkingEnum::AFTERNOON][] = $working;
                    continue;
                }
            }

            if ($working->getStartAt() == $date && $working->getPeriodStartAt() === WorkingEnum::AFTERNOON) {
                $workingsSplit[WorkingEnum::AFTERNOON][] = $working;
                continue;
            }

            if ($working->getEndAt() == $date && $working->getPeriodEndAt() === WorkingEnum::MORNING) {
                $workingsSplit[WorkingEnum::MORNING][] = $working;
                continue;
            }

            $workingsSplit[WorkingEnum::DAY][] = $working;
        }

        return [
            'userCounterPerLocation' => $userCounterPerLocation,
            'locationsCounter' => $locationsCounter,
            'workingsSplit' => $workingsSplit
        ];
    }

    /**
     * Retourne toutes les demandes de télétravail d'un mois, format return pour la calendar
     * @param $month
     * @param $year
     * @return array
     * @throws \Exception
     */
    public function getWorkingsByMonth($month, $year)
    {
        $startAt = new \DateTime($year . '-' . $month . '-01');
        $endAt = clone $startAt;
        $endAt->modify('last day of this month');

        $workings = $this->em->getRepository(Working::class)->getWorkingsByMonth($startAt, $endAt);

        $result = [];
        foreach ($workings as $working) {
            $result[] = [
                'fullname' => $working->getEmployee()->getFullName(),
                'startAt' => $working->getStartAt()->format('Y-m-d'),
                'endAt' => $working->getEndAt()->format('Y-m-d')
            ];
        }

        return $result;
    }

    /**
     * @param $user
     * @param $month
     * @param $location
     * @return mixed
     * @throws \Exception
     */
    public function getWorkingDataGraph($user, $month, $location)
    {
        $startAt = new \DateTime($month . '-01');
        $startAt->modify('first day of this month');
        $endAt = clone $startAt;
        $endAt->modify('last day of this month');

        if (!$month) {
            $startAt->modify('-11 months');
        }

        $workings =  $this->em->getRepository(Working::class)->getWorkingDataGraph($user, $startAt, $endAt, $location);
        list($result, $axisY) = $this->getTemplateResult($startAt, $endAt, $location);

        /** @var Working $working */
        foreach ($workings as $working) {
            if ($startAt->format('m') === $endAt->format('m')) {
                while ($working->getStartAt() <= $working->getEndAt()) {
                    if (array_key_exists($working->getStartAt()->format('Y-m-d'), $result[$working->getEmployee()->getLocation()])) {
                        $result[$working->getEmployee()->getLocation()][$working->getStartAt()->format('Y-m-d')]++;
                    }

                    $working->getStartAt()->modify('+1 day');
                }

            } else {
                while ($working->getStartAt() <= $working->getEndAt()) {
                    if (array_key_exists($working->getStartAt()->format('Y-m'), $result[$working->getEmployee()->getLocation()])) {
                        $result[$working->getEmployee()->getLocation()][$working->getStartAt()->format('Y-m')]++;
                    }

                    $working->getStartAt()->modify('+1 month');
                }
            }
        }

        return [$result, $this->formatAxis($axisY, $month)];
    }

    private function formatAxis($axis, $month) {
        if ($month) {
            return array_map(function ($value){
                return substr($value, -2);
            }, $axis);
        }

        $months = [
            "01" => 'Janvier',
            "02" => 'Février',
            "03" => 'Mars',
            "04" => 'Avril',
            "05" => 'Mai',
            "06" => 'Juin',
            "07" => 'Juillet',
            "08" => 'Août',
            "09" => 'Septembre',
            "10" => 'Octobre',
            "11" => 'Novembre',
            "12" => 'Décembre'
        ];

        return array_reverse(array_map(function ($values) use ($months) {
            $month = explode('-', $values);
            return $months[$month[1]];
        }, $axis));
    }

    /**
     * @param \DateTime $startAt
     * @param \DateTime $endAt
     * @param $location
     * @return array
     */
    private function getTemplateResult(\DateTime $startAt, \DateTime $endAt, $location)
    {
        if (!$location) {
            $location = array_values($this->em->getRepository(User::class)->getAllLocations());
        } else {
            $location = [$location];
        }

        $template = $this->getTemplateArray($startAt, $endAt);
        $templateResult = [];

        foreach ($location as $loc) {
            $templateResult[$loc] = $template;
        }

        return [$templateResult, array_keys($template)];
    }

    /**
     * @param \DateTime $startAt
     * @param \DateTime $endAt
     * @return array
     */
    private function getTemplateArray(\DateTime $startAt, \DateTime $endAt)
    {
        $template = [];

        if ($startAt->format('m') === $endAt->format('m')) {
            while($startAt <= $endAt) {
                $template[$startAt->format('Y-m-d')] = 0;
                $startAt->modify('+1 day');
            }
        } else {
            for ($i = 0; $i <= 12; $i++) {
                $template[$endAt->format('Y-m')] = 0;
                $endAt->modify('-1 month');
            }
        }

        return $template;
    }
}