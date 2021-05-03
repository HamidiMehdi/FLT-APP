<?php

namespace App\Service;

use App\Entity\AnnualInterview;
use App\Entity\ProInterview;
use App\Entity\User;
use App\Enum\EmailEnum;
use App\Enum\InterviewEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\NamedAddress;
use Twig\Environment;

class InterviewService
{
    /**  @var EntityManagerInterface */
    private $em;

    /** @var MailerInterface  */
    private $mailer;

    /** @var Environment */
    private $templating;

    /**
     * InterviewService constructor.
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
     * @param User $user
     * @return mixed
     */
    public function getCurrentProInterviewByUser(User $user)
    {
        return $this->em->getRepository(ProInterview::class)->getCurrentProInterviewByUser($user);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getCurrentAnnualInterviewByUser(User $user)
    {
        return $this->em->getRepository(AnnualInterview::class)->getCurrentAnnualInterviewByUser($user);
    }

    /**
     * @param $interviewType
     * @param $userType
     * @return null
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function relaunchUser($interviewType, $userType)
    {
        if ($userType === InterviewEnum::EMPLOYEE) {
            return $this->relaunchEmployee($interviewType);
        }

        return $this->relaunchManager($interviewType);
    }

    /**
     * Relance des courriels pour les collaborateurs
     * @param $interviewType
     * @return int
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function relaunchEmployee($interviewType)
    {
        $users = null;
        if ($interviewType === InterviewEnum::INTERVIEW_PRO) {
            $users = $this->em->getRepository(User::class)->getProInterviewNotCreatedByYear(date('Y'));
        } else {
            $users = $this->em->getRepository(User::class)->getAnnualInterviewNotCreatedByYear(date('Y'));
        }

        $counter = 0;
        foreach ($users as $user) {
            $user = $users = $this->em->getRepository(User::class)->find($user);
            if (!$user) {
               continue;
            }

            $email = (new Email())
                ->from(new NamedAddress(EmailEnum::FROM, EmailEnum::LABEL_FROM))
                ->to($user->getEmail())
                ->subject('Relance automatique - Portail FLT')
                ->html($this->templating->render('interviews/email/relaunch.html.twig', [
                    'user' => $user,
                    'interviewType' => $interviewType,
                    'userType' => InterviewEnum::EMPLOYEE
                ]));

            $this->mailer->send($email);
            $counter++;
        }

        return $counter;
    }

    /**
     * Relance des courriels pour les managers
     * @param $interviewType
     * @return int
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function relaunchManager($interviewType)
    {
        $users = null;
        if ($interviewType === InterviewEnum::INTERVIEW_PRO) {
            $users = $this->em->getRepository(User::class)->getProInterviewNotValidatedByYear(date('Y'));
        } else {
            $users = $this->em->getRepository(User::class)->getAnnualInterviewNotValidatedByYear(date('Y'));
        }

        $counter = 0;
        $managers = [];
        foreach ($users as $user) {
            /** @var User $user */
            $user = $users = $this->em->getRepository(User::class)->find($user);
            if (!$user) {
                continue;
            }

            foreach ($user->getManager() as $manager) {
                $manager = $manager->getManager();
                if (in_array($manager->getId(), $managers)) {
                    continue;
                }

                $email = (new Email())
                    ->from(new NamedAddress(EmailEnum::FROM, EmailEnum::LABEL_FROM))
                    ->to($manager->getEmail())
                    ->subject('Relance automatique - Portail FLT')
                    ->html($this->templating->render('interviews/email/relaunch.html.twig', [
                        'user' => $manager,
                        'interviewType' => $interviewType,
                        'userType' => InterviewEnum::MANAGER
                    ]));

                $this->mailer->send($email);
                $managers[] = $manager->getId();
                $counter++;
            }
        }

        return $counter;
    }

}