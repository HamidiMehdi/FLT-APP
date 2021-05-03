<?php

namespace App\Controller\Interviews;

use App\Entity\AnnualInterview;
use App\Entity\Manager;
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
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class InterviewProgressController extends AbstractController
{
    /**
     * @Route("/entretien/liste", name="interview_in_progress_list")
     * @return Response
     */
    public function interviewProgressList()
    {
        $proInterview = $this->getDataInterviewProgress($this->getUser(), InterviewEnum::INTERVIEW_PRO);
        $proInterviewData = [
            'data' => $proInterview,
            'pagination' => [
                'pages' => count($proInterview) > 0 ? ceil(count($proInterview) / PaginationEnum::DEFAULT_NUMBER_ELEMENT) : 1,
                'current_page' => 1,
            ]
        ];

        $annualInterview = $this->getDataInterviewProgress($this->getUser(), InterviewEnum::INTERVIEW_ANNUAL);
        $annualInterviewData = [
            'data' => $annualInterview,
            'pagination' => [
                'pages' => count($annualInterview) > 0 ? ceil(count($annualInterview) / PaginationEnum::DEFAULT_NUMBER_ELEMENT) : 1,
                'current_page' => 1,
            ]
        ];

        return $this->render('interviews/interview_progress/interview_list.html.twig', [
            'proInterview' => $proInterviewData,
            'annualInterview' => $annualInterviewData
        ]);
    }

    /**
     * @Route("/entretien/recherche", name="interview_in_progress_search")
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

        $data = $this->getDataInterviewProgress($this->getUser(), $nav, $offset, $search);

        return new JsonResponse(
            [
                'html' => $this->renderView($this->getTemplate($nav), [
                    'data' => $data,
                    'pagination' => [
                        'pages' => count($data) > 0 ? ceil(count($data) / PaginationEnum::DEFAULT_NUMBER_ELEMENT) : 1,
                        'current_page' => $page,
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
            InterviewEnum::INTERVIEW_ANNUAL => 'interviews/interview_progress/partial/annual_interview.html.twig',
            InterviewEnum::INTERVIEW_PRO => 'interviews/interview_progress/partial/pro_interview.html.twig'
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
    private function getDataInterviewProgress(User $user, $nav, $offset = 0, $search = null)
    {
        switch ($nav) {
            case InterviewEnum::INTERVIEW_ANNUAL :
                return $this->em()->getRepository(AnnualInterview::class)->getAnnualInterviewByManager($user, $offset, $search);
            case InterviewEnum::INTERVIEW_PRO :
                return $this->em()->getRepository(ProInterview::class)->getProInterviewByManager($user, $offset, $search);
        }
    }

    /**
     * @Route("/passage/entretien/professionnel/{id}/étape/1", name="interview_pro_in_progress_p1")
     * @param Request $request
     * @param UserService $userService
     * @param $id
     * @return Response
     * @throws \Exception
     */
    public function interviewProInProgressP1(Request $request, UserService $userService, $id)
    {
        $pi = $this->em()->getRepository(ProInterview::class)->find($id);
        $this->checkAccessInterview($pi);

        $form = $this->createForm(InterviewProP1Type::class, $pi);
        $form->handleRequest($request);
        list($firstManager, $secondManager, $managerFunc) = $userService->getAllManagersByEmployee($pi->getEmployee());
        $dateNow = new \DateTime('now');

        if ($form->isSubmitted() && $form->isValid()) {
            $pi->getEmployee()->setBirthday($form->get('birthday')->getData());
            $pi->getEmployee()->setDateEntered($form->get('dateEntered')->getData());
            $pi->setInterviewDate($dateNow);
            $pi->setLeadBy($this->getUser());

            $this->em()->persist($pi->getEmployee());
            $this->em()->persist($pi);
            $this->em()->flush();

            return $this->redirectToRoute('interview_pro_in_progress_p2', ['id' => $pi->getId()]);
        }

        return $this->render('interviews/interview_progress/pro/interview_pro_in_progress_p1.html.twig', [
            'form' => $form->createView(),
            'managerFunc' => $managerFunc,
            'pi' => $pi,
            'dateNow' => $dateNow
        ]);
    }

    /**
     * @Route("/passage/entretien/professionnel/{id}/étape/2", name="interview_pro_in_progress_p2")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function interviewProInProgressP2(Request $request, $id)
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

            return $this->redirectToRoute('interview_pro_in_progress_p3', ['id' => $pi->getId()]);
        }

        return $this->render('interviews/interview_progress/pro/interview_pro_in_progress_p2.html.twig', [
            'form' => $form->createView(),
            'pi' => $pi
        ]);
    }

    /**
     * @Route("/passage/entretien/professionnel/{id}/étape/3", name="interview_pro_in_progress_p3")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function interviewProInProgressP3(Request $request, $id)
    {
        /** @var ProInterview $pi */
        $pi = $this->em()->getRepository(ProInterview::class)->find($id);
        $this->checkAccessInterview($pi);

        $form = $this->createForm(InterviewProP3Type::class, $pi, ['in_progress' => true]);
        $form->handleRequest($request);
        $dateNow = new \DateTime('now');

        if ($form->isSubmitted() && $form->isValid()) {
            $pi->setInterviewValidated(new \DateTime('now'));
            $pi->setManagerDateSignature($dateNow);
            $pi->setSecondManagerDateSignature($dateNow);
            $pi->setLeadBy($this->getUser());
            if (!$pi->getAcceptSecondManager()) {
                $pi->setSecondManagerDateSignature(null);
                $pi->setSecondManage(null);
                $pi->setSecondManagerSignature(null);
            }

            $this->em()->persist($pi);
            $this->em()->flush();

            return $this->redirectToRoute('interview_in_progress_list');
        }

        return $this->render('interviews/interview_progress/pro/interview_pro_in_progress_p3.html.twig', [
            'form' => $form->createView(),
            'pi' => $pi,
            'dateNow' => $dateNow
        ]);
    }

    /**
     * @Route("/passage/entretien/annuel/{id}/étape/1", name="interview_annual_in_progress_p1")
     * @param Request $request
     * @param UserService $userService
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function interviewAnnualInProgressP1(Request $request, UserService $userService, $id)
    {
        /** @var AnnualInterview $ai */
        $ai = $this->em()->getRepository(AnnualInterview::class)->find($id);
        $this->checkAccessInterview($ai);

        $form = $this->createForm(InterviewAnnualP1Type::class, $ai);
        $form->handleRequest($request);

        list($firstManager, $secondManager, $managerFunc) = $userService->getAllManagersByEmployee($ai->getEmployee());
        $prevAi = $this->em()->getRepository(AnnualInterview::class)->getPrevAnnualInterview($ai);
        $dateNow = new \DateTime('now');

        if ($form->isSubmitted() && $form->isValid()) {
            $ai->getEmployee()->setBirthday($form->get('birthday')->getData());
            $ai->getEmployee()->setDateEntered($form->get('dateEntered')->getData());
            $ai->setInterviewDate($dateNow);
            $ai->setLeadBy($this->getUser());

            $this->em()->persist($ai->getEmployee());
            $this->em()->persist($ai);
            $this->em()->flush();

            return $this->redirectToRoute('interview_annual_in_progress_p2', ['id' => $ai->getId()]);
        }

        return $this->render('interviews/interview_progress/annual/interview_annual_in_progress_p1.html.twig', [
            'form' => $form->createView(),
            'secondManager' => $secondManager,
            'managerFunc' => $managerFunc,
            'ai' => $ai,
            'prevAi' => $prevAi,
            'dateNow' => $dateNow
        ]);
    }

    /**
     * @Route("/passage/entretien/annuel/{id}/étape/2", name="interview_annual_in_progress_p2")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function interviewAnnualInProgressP2(Request $request, $id)
    {
        /** @var AnnualInterview $ai */
        $ai = $this->em()->getRepository(AnnualInterview::class)->find($id);
        $this->checkAccessInterview($ai);

        $form = $this->createForm(InterviewAnnualP2Type::class, $ai, ['in_progress' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->em()->persist($ai);
            $this->em()->flush();

            return $this->redirectToRoute('interview_annual_in_progress_p3', ['id' => $ai->getId()]);
        }

        return $this->render('interviews/interview_progress/annual/interview_annual_in_progress_p2.html.twig', [
            'form' => $form->createView(),
            'ai' => $ai
        ]);
    }

    /**
     * @Route("/passage/entretien/annuel/{id}/étape/3", name="interview_annual_in_progress_p3")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function interviewAnnualInProgressP3(Request $request, $id)
    {
        /** @var AnnualInterview $ai */
        $ai = $this->em()->getRepository(AnnualInterview::class)->find($id);
        $this->checkAccessInterview($ai);

        $form = $this->createForm(InterviewAnnualP3Type::class, $ai, ['in_progress' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->em()->persist($ai);
            $this->em()->flush();

            return $this->redirectToRoute('interview_annual_in_progress_p4', ['id' => $ai->getId()]);
        }

        return $this->render('interviews/interview_progress/annual/interview_annual_in_progress_p3.html.twig', [
            'form' => $form->createView(),
            'ai' => $ai
        ]);
    }

    /**
     * @Route("/passage/entretien/annuel/{id}/étape/4", name="interview_annual_in_progress_p4")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function interviewAnnualInProgressP4(Request $request, $id)
    {
        /** @var AnnualInterview $ai */
        $ai = $this->em()->getRepository(AnnualInterview::class)->find($id);
        $this->checkAccessInterview($ai);

        $form = $this->createForm(InterviewAnnualP4Type::class, $ai);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->em()->persist($ai);
            $this->em()->flush();

            return $this->redirectToRoute('interview_annual_in_progress_p5', ['id' => $ai->getId()]);
        }

        return $this->render('interviews/interview_progress/annual/interview_annual_in_progress_p4.html.twig', [
            'form' => $form->createView(),
            'ai' => $ai
        ]);
    }

    /**
     * @Route("/passage/entretien/annuel/{id}/étape/5", name="interview_annual_in_progress_p5")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function interviewAnnualInProgressP5(Request $request, $id)
    {
        /** @var AnnualInterview $ai */
        $ai = $this->em()->getRepository(AnnualInterview::class)->find($id);
        $this->checkAccessInterview($ai);

        $form = $this->createForm(InterviewAnnualP5Type::class, $ai);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->em()->persist($ai);
            $this->em()->flush();
            return $this->redirectToRoute('interview_annual_in_progress_p6', ['id' => $ai->getId()]);
        }

        return $this->render('interviews/interview_progress/annual/interview_annual_in_progress_p5.html.twig', [
            'form' => $form->createView(),
            'ai' => $ai
        ]);
    }

    /**
     * @Route("/passage/entretien/annuel/{id}/étape/6", name="interview_annual_in_progress_p6")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function interviewAnnualInProgressP6(Request $request, $id)
    {
        /** @var AnnualInterview $ai */
        $ai = $this->em()->getRepository(AnnualInterview::class)->find($id);
        $this->checkAccessInterview($ai);

        $form = $this->createForm(InterviewAnnualP6Type::class, $ai, ['in_progress' => true]);
        $form->handleRequest($request);

        $dateNow = new \DateTime('now');

        if ($form->isSubmitted()) {
            $ai->setInterviewValidated(new \DateTime('now'));
            $ai->setManagerDateSignature($dateNow);
            $ai->setLeadBy($this->getUser());

            $this->em()->persist($ai);
            $this->em()->flush();

            return $this->redirectToRoute('interview_in_progress_list');
        }

        return $this->render('interviews/interview_progress/annual/interview_annual_in_progress_p6.html.twig', [
            'form' => $form->createView(),
            'ai' => $ai,
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

        if (!$interview->getOwnInterviewValidated()) {
            throw new AccessDeniedHttpException('This entity is not yet validated');
        }

        if ($interview->getInterviewValidated()) {
            throw new AccessDeniedHttpException('This entity already validated');
        }

        $usersAllowed = $this->em()->getRepository(User::class)->getManagersIdByUser($interview->getEmployee());
        if (!in_array($this->getUser()->getId(), $usersAllowed) && !$this->getUser()->isAdmin()) {
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