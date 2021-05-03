<?php

namespace App\Controller\Interviews;

use App\Entity\AnnualInterview;
use App\Entity\ProInterview;
use App\Entity\User;
use App\Enum\InterviewEnum;
use App\Enum\PaginationEnum;
use App\Form\InterviewAnnualP1Type;
use App\Form\InterviewAnnualP2Type;
use App\Form\InterviewAnnualP3Type;
use App\Form\InterviewAnnualP4Type;
use App\Form\InterviewAnnualP5Type;
use App\Form\InterviewAnnualP6Type;
use App\Form\InterviewProP1Type;
use App\Form\InterviewProP2Type;
use App\Form\InterviewProP3Type;
use App\Service\InterviewService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class InterviewController extends AbstractController
{
    /**
     * @Route("/entretien/choix", name="choice_interviews")
     * @param InterviewService $interviewService
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function choiceInterview(InterviewService $interviewService)
    {
        $proInterview = $this->getDataOwnInterview($this->getUser(), InterviewEnum::INTERVIEW_PRO);
        $proInterviewData = [
            'data' => $proInterview,
            'pagination' => [
                'pages' => count($proInterview) > 0 ? ceil(count($proInterview) / PaginationEnum::DEFAULT_NUMBER_ELEMENT) : 1,
                'current_page' => 1
            ]
        ];

        $annualInterview = $this->getDataOwnInterview($this->getUser(), InterviewEnum::INTERVIEW_ANNUAL);
        $annualInterviewData = [
            'data' => $annualInterview,
            'pagination' => [
                'pages' => count($annualInterview) > 0 ? ceil(count($annualInterview) / PaginationEnum::DEFAULT_NUMBER_ELEMENT) : 1,
                'current_page' => 1
            ]
        ];

        return $this->render('interviews/interview/choice.html.twig', [
            'currentPi' => $interviewService->getCurrentProInterviewByUser($this->getUser()),
            'currentAi' => $interviewService->getCurrentAnnualInterviewByUser($this->getUser()),
            'proInterview' => $proInterviewData,
            'annualInterview' => $annualInterviewData
        ]);
    }

    /**
     * @Route("/entretien/recherche/utilisateur", name="interview_search")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function getDataInterviewAjax(Request $request)
    {
        $nav = $request->get('nav');
        $page = $request->get('page');
        $offset = $page === 1 ? 0 : PaginationEnum::DEFAULT_NUMBER_ELEMENT * ($page - 1);
        $search = $request->get('search');

        $data = $this->getDataOwnInterview($this->getUser(), $nav, $offset, $search);

        return new JsonResponse(
            [
                'html' => $this->renderView($this->getTemplate($nav), [
                    'data' => $data,
                    'pagination' => [
                        'pages' => count($data) > 0 ? ceil(count($data) / PaginationEnum::DEFAULT_NUMBER_ELEMENT) : 1,
                        'current_page' => $page
                    ]
                ])
            ]
        );
    }

    /**
     * @param $nav
     * @return mixed
     */
    private function getTemplate($nav)
    {
        $templates = [
            InterviewEnum::INTERVIEW_ANNUAL => 'interviews/interview/partial/annual_interview.html.twig',
            InterviewEnum::INTERVIEW_PRO => 'interviews/interview/partial/pro_interview.html.twig'
        ];

        return $templates[$nav];
    }

    /**
     * @param User $user
     * @param $nav
     * @param int $offset
     * @param null $search
     * @return array
     */
    private function getDataOwnInterview(User $user, $nav, $offset = 0, $search = null)
    {
        switch ($nav) {
            case InterviewEnum::INTERVIEW_ANNUAL :
                return $this->em()->getRepository(AnnualInterview::class)->getAnnualInterviewByUser($user, $offset, $search);
            case InterviewEnum::INTERVIEW_PRO :
                return $this->em()->getRepository(ProInterview::class)->getProInterviewByUser($user, $offset, $search);
        }
    }

    /**
     * @Route("/entretien/annuel/étape/1", name="interview_annual_p1")
     * @param Request $request
     * @param UserService $userService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function interviewAnnualP1(Request $request, UserService $userService)
    {
        $ai = $request->query->get('ai_id');
        if ($ai !== null && $ai > 0) {
            $ai = $this->em()->getRepository(AnnualInterview::class)->find($ai);
            $this->checkAccessInterview($ai);
        } else {
            $ai = new AnnualInterview();
        }

        $form = $this->createForm(InterviewAnnualP1Type::class, $ai);
        $form->handleRequest($request);
        list($firstManager, $secondManager, $managerFunc) = $userService->getAllManagersByEmployee($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getUser()->setBirthday($form->get('birthday')->getData());
            $this->getUser()->setDateEntered($form->get('dateEntered')->getData());
            $ai->setEmployee($this->getUser());
            $ai->setManager($firstManager);

            $this->em()->persist($this->getUser());
            $this->em()->persist($ai);
            $this->em()->flush();

            return $this->redirectToRoute('interview_annual_p2', ['id' => $ai->getId()]);
        }

        return $this->render('interviews/interview/annual/interview_annual_p1.html.twig', [
            'form' => $form->createView(),
            'firstManager' => $firstManager,
            'secondManager' => $secondManager,
            'managerFunc' => $managerFunc
        ]);
    }

    /**
     * @Route("/entretien/annuel/{id}/étape/2", name="interview_annual_p2")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function interviewAnnualP2(Request $request, $id)
    {
        /** @var ProInterview $pi */
        $ai = $this->em()->getRepository(AnnualInterview::class)->find($id);
        $this->checkAccessInterview($ai);

        $form = $this->createForm(InterviewAnnualP2Type::class, $ai);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->em()->persist($ai);
            $this->em()->flush();

            return $this->redirectToRoute('interview_annual_p3', ['id' => $ai->getId()]);
        }

        return $this->render('interviews/interview/annual/interview_annual_p2.html.twig', [
            'form' => $form->createView(),
            'ai' => $ai
        ]);
    }

    /**
     * @Route("/entretien/annuel/{id}/étape/3", name="interview_annual_p3")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function interviewAnnualP3(Request $request, $id)
    {
        /** @var ProInterview $pi */
        $ai = $this->em()->getRepository(AnnualInterview::class)->find($id);
        $this->checkAccessInterview($ai);

        $form = $this->createForm(InterviewAnnualP3Type::class, $ai);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->em()->persist($ai);
            $this->em()->flush();

            return $this->redirectToRoute('interview_annual_p4', ['id' => $ai->getId()]);
        }

        return $this->render('interviews/interview/annual/interview_annual_p3.html.twig', [
            'form' => $form->createView(),
            'ai' => $ai
        ]);
    }

    /**
     * @Route("/entretien/annuel/{id}/étape/4", name="interview_annual_p4")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function interviewAnnualP4(Request $request, $id)
    {
        /** @var ProInterview $pi */
        $ai = $this->em()->getRepository(AnnualInterview::class)->find($id);
        $this->checkAccessInterview($ai);

        $form = $this->createForm(InterviewAnnualP4Type::class, $ai);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->em()->persist($ai);
            $this->em()->flush();

            return $this->redirectToRoute('interview_annual_p5', ['id' => $ai->getId()]);
        }

        return $this->render('interviews/interview/annual/interview_annual_p4.html.twig', [
            'form' => $form->createView(),
            'ai' => $ai
        ]);
    }

    /**
     * @Route("/entretien/annuel/{id}/étape/5", name="interview_annual_p5")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function interviewAnnualP5(Request $request, $id)
    {
        /** @var ProInterview $pi */
        $ai = $this->em()->getRepository(AnnualInterview::class)->find($id);
        $this->checkAccessInterview($ai);

        $form = $this->createForm(InterviewAnnualP5Type::class, $ai);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->em()->persist($ai);
            $this->em()->flush();
            return $this->redirectToRoute('interview_annual_p6', ['id' => $ai->getId()]);
        }

        return $this->render('interviews/interview/annual/interview_annual_p5.html.twig', [
            'form' => $form->createView(),
            'ai' => $ai
        ]);
    }

    /**
     * @Route("/entretien/annuel/{id}/étape/6", name="interview_annual_p6")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function interviewAnnualP6(Request $request, $id)
    {
        /** @var ProInterview $pi */
        $ai = $this->em()->getRepository(AnnualInterview::class)->find($id);
        $this->checkAccessInterview($ai);

        $form = $this->createForm(InterviewAnnualP6Type::class, $ai);
        $form->handleRequest($request);
        $dateNow = new \DateTime('now');

        if ($form->isSubmitted()) {
            $ai->setEmployeeDateSignature($dateNow);
            $ai->setOwnInterviewValidated($dateNow);

            $this->em()->persist($ai);
            $this->em()->flush();
            return $this->redirectToRoute('choice_interviews');
        }

        return $this->render('interviews/interview/annual/interview_annual_p6.html.twig', [
            'form' => $form->createView(),
            'ai' => $ai,
            'dateNow' => $dateNow
        ]);
    }

    /**
     * @Route("/entretien/professionnel/étape/1", name="interview_pro_p1")
     * @param Request $request
     * @param UserService $userService
     * @return Response
     */
    public function interviewProP1(Request $request, UserService $userService)
    {
        $pi = $request->query->get('pi_id');
        if ($pi !== null && $pi > 0) {
            $pi = $this->em()->getRepository(ProInterview::class)->find($pi);
            $this->checkAccessInterview($pi);
        } else {
            $pi = new ProInterview();
        }

        $form = $this->createForm(InterviewProP1Type::class, $pi);
        $form->handleRequest($request);
        list($firstManager, $secondManager, $managerFunc) = $userService->getAllManagersByEmployee($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getUser()->setBirthday($form->get('birthday')->getData());
            $this->getUser()->setDateEntered($form->get('dateEntered')->getData());
            $pi->setEmployee($this->getUser());
            $pi->setManager($firstManager);
            $pi->setSecondManage($secondManager);

            $this->em()->persist($pi);
            $this->em()->persist($this->getUser());
            $this->em()->flush();

            return $this->redirectToRoute('interview_pro_p2', ['id' => $pi->getId()]);
        }

        return $this->render('interviews/interview/pro/interview_pro_p1.html.twig', [
            'form' => $form->createView(),
            'firstManager' => $firstManager,
            'secondManager' => $secondManager,
            'managerFunc' => $managerFunc
        ]);
    }

    /**
     * @Route("/entretien/professionnel/{id}/étape/2", name="interview_pro_p2")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function interviewProP2(Request $request, $id)
    {
        /** @var ProInterview $pi */
        $pi = $this->em()->getRepository(ProInterview::class)->find($id);
        $this->checkAccessInterview($pi);

        $form = $this->createForm(InterviewProP2Type::class, $pi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($pi->getFormationWishes() == false) {
                $pi->setFormationWishesType(null);
                $pi->setFormationWishesDesc(null);
                $pi->setFormationWishesExpectedTime(null);
            }
            if ($pi->getGeographicMobility() == false) {
                $pi->setGeographicMobilityLocation(null);
                $pi->setGeographicMobilityExpectedTime(null);
            }

            $this->em()->persist($pi);
            $this->em()->flush();

            return $this->redirectToRoute('interview_pro_p3', ['id' => $pi->getId()]);
        }

        return $this->render('interviews/interview/pro/interview_pro_p2.html.twig', [
            'form' => $form->createView(),
            'pi' => $pi
        ]);
    }

    /**
     * @Route("/entretien/professionnel/{id}/étape/3", name="interview_pro_p3")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function interviewProP3(Request $request, $id)
    {
        /** @var ProInterview $pi */
        $pi = $this->em()->getRepository(ProInterview::class)->find($id);
        $this->checkAccessInterview($pi);

        $form = $this->createForm(InterviewProP3Type::class, $pi);
        $form->handleRequest($request);
        $dateNow = new \DateTime('now');

        if ($form->isSubmitted() && $form->isValid()) {
            $pi->setEmployeeDateSignature($dateNow);
            $pi->setOwnInterviewValidated($dateNow);
            $this->em()->persist($pi);
            $this->em()->flush();

            return $this->redirectToRoute('choice_interviews');
        }

        return $this->render('interviews/interview/pro/interview_pro_p3.html.twig', [
            'form' => $form->createView(),
            'pi' => $pi,
            'dateNow' => $dateNow
        ]);
    }

    /**
     * @param $interview
     */
    private function checkAccessInterview($interview)
    {
        if (!$interview) {
            throw $this->createNotFoundException('Unable to find this entity.');
        }

        if ($interview->getInterviewValidated()) {
            throw new AccessDeniedHttpException('This entity already validated');
        }

        if ($this->getUser()->getId() !== $interview->getEmployee()->getId()) {
            throw new AccessDeniedHttpException('Access denied');
        }
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    private function em()
    {
        return $this->getDoctrine()->getManager();
    }
}