<?php

namespace App\Controller\Interviews;

use App\Entity\AnnualInterview;
use App\Entity\ProInterview;
use App\Entity\User;
use App\Enum\InterviewEnum;
use App\Enum\PaginationEnum;
use App\Service\PdfService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class InterviewValidatedController extends AbstractController
{
    /**
     * @Route("/entretien/validé/liste", name="interview_validated_list")
     * @param Request $request
     * @return Response
     */
    public function interviewProgressList(Request $request)
    {
        $proInterview = $this->getDataInterviewValidated($this->getUser(), InterviewEnum::INTERVIEW_PRO);
        $proInterviewData = [
            'data' => $proInterview,
            'pagination' => [
                'pages' => count($proInterview) > 0 ? ceil(count($proInterview) / PaginationEnum::DEFAULT_NUMBER_ELEMENT) : 1,
                'current_page' => 1
            ]
        ];

        $annualInterview = $this->getDataInterviewValidated($this->getUser(), InterviewEnum::INTERVIEW_ANNUAL);
        $annualInterviewData = [
            'data' => $annualInterview,
            'pagination' => [
                'pages' => count($annualInterview) > 0 ? ceil(count($annualInterview) / PaginationEnum::DEFAULT_NUMBER_ELEMENT) : 1,
                'current_page' => 1
            ]
        ];

        return $this->render('interviews/interview_validated/interview_list.html.twig', [
            'proInterview' => $proInterviewData,
            'annualInterview' => $annualInterviewData,
        ]);
    }

    /**
     * @Route("/entretien/validé/recherche", name="interview_validated_search")
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

        $data = $this->getDataInterviewValidated($this->getUser(), $nav, $offset, $search);

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
            InterviewEnum::INTERVIEW_ANNUAL => 'interviews/interview_validated/partial/annual_interview.html.twig',
            InterviewEnum::INTERVIEW_PRO => 'interviews/interview_validated/partial/pro_interview.html.twig'
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
    private function getDataInterviewValidated(User $user, $nav, $offset = 0, $search = null)
    {
        switch ($nav) {
            case InterviewEnum::INTERVIEW_ANNUAL :
                return $this->em()->getRepository(AnnualInterview::class)->getAnnualInterviewValidatedByManager($user, $offset, $search);
            case InterviewEnum::INTERVIEW_PRO :
                return $this->em()->getRepository(ProInterview::class)->getProInterviewValidatedByManager($user, $offset, $search);
        }
    }

    /**
     * @Route("/entretien/professionnel/{id}/validé/étape/{step}", name="show_interview_pro_validated")
     * @param UserService $userService
     * @param $id
     * @param $step
     * @return Response
     * @throws \Exception
     */
    public function interviewProValidated(UserService $userService, $id, $step)
    {
        $pi = $this->em()->getRepository(ProInterview::class)->find($id);
        $this->checkAccessInterview($pi);

        list($firstManager, $secondManager, $managerFunc) = $userService->getAllManagersByEmployee($pi->getEmployee());

        return $this->render(
            $this->getProInterviewTemplateByStep($step), [
                'managerFunc' => $managerFunc,
                'pi' => $pi,
            ]
        );
    }

    /**
     * @param $step
     * @return string|null
     */
    private function getProInterviewTemplateByStep($step): ?string
    {
        $templates = [
            1 => 'interviews/interview_validated/pro/interview_pro_validated_p1.html.twig',
            2 => 'interviews/interview_validated/pro/interview_pro_validated_p2.html.twig',
            3 => 'interviews/interview_validated/pro/interview_pro_validated_p3.html.twig'
        ];

        return $templates[$step];
    }

    /**
     * @Route("/entretien/annuel/{id}/validé/étape/{step}", name="show_interview_annual_validated")
     * @param UserService $userService
     * @param $id
     * @param $step
     * @return Response
     * @throws \Exception
     */
    public function interviewAnnualValidated(UserService $userService, $id, $step)
    {
        $ai = $this->em()->getRepository(AnnualInterview::class)->find($id);
        $this->checkAccessInterview($ai);

        list($firstManager, $secondManager, $managerFunc) = $userService->getAllManagersByEmployee($ai->getEmployee());
        $prevAi = $this->em()->getRepository(AnnualInterview::class)->getPrevAnnualInterview($ai);

        return $this->render(
            $this->getAnnualInterviewTemplateByStep($step), [
                'secondManager' => $secondManager,
                'managerFunc' => $managerFunc,
                'prevAi' => $prevAi,
                'ai' => $ai,
            ]
        );
    }

    /**
     * @param $step
     * @return string|null
     */
    private function getAnnualInterviewTemplateByStep($step): ?string
    {
        $templates = [
            1 => 'interviews/interview_validated/annual/interview_annual_validated_p1.html.twig',
            2 => 'interviews/interview_validated/annual/interview_annual_validated_p2.html.twig',
            3 => 'interviews/interview_validated/annual/interview_annual_validated_p3.html.twig',
            4 => 'interviews/interview_validated/annual/interview_annual_validated_p4.html.twig',
            5 => 'interviews/interview_validated/annual/interview_annual_validated_p5.html.twig',
            6 => 'interviews/interview_validated/annual/interview_annual_validated_p6.html.twig'
        ];

        return $templates[$step];
    }

    /**
     * @Route("/telechargement/entretien/professionnel/{id}", name="download_pro_interview")
     * @param UserService $userService
     * @param PdfService $pdfService
     * @param $id
     * @return Response
     * @throws \Exception
     */
    public function downloadProInterview(UserService $userService, PdfService $pdfService, $id)
    {
        $pi = $this->em()->getRepository(ProInterview::class)->find($id);
        $this->checkAccessDownloadInterview($pi);

        list($firstManager, $secondManager, $managerFunc) = $userService->getAllManagersByEmployee($pi->getEmployee());

        $html = $this->renderView('interviews/pdf/interview_pro.html.twig', [
            'managerFunc' => $managerFunc,
            'pi' => $pi
        ]);

        $options = [
            'footer-html' => $this->renderView('interviews/pdf/footer/footer_pdf.html.twig'),
            'header-html' => $this->renderView('interviews/pdf/header/header_pro_pdf.html.twig', ['pi' => $pi]),
            'margin-top' => 25,
        ];

        $pdf = $pdfService->generatePdf($html, $options);
        $name = 'Entretien professionnel ' . $pi->getCreatedAt()->format('Y') . ' - ' . $pi->getEmployee()->getFullName() . '.pdf';

        return $this->responseFile($pdf, $name, 'application/pdf');
    }

    /**
     * @Route("/telechargement/entretien/annuel/{id}", name="download_annual_interview")
     * @param UserService $userService
     * @param PdfService $pdfService
     * @param $id
     * @return Response
     * @throws \Exception
     */
    public function downloadAnnualInterview(UserService $userService, PdfService $pdfService, $id)
    {
        $ai = $this->em()->getRepository(AnnualInterview::class)->find($id);
        $this->checkAccessDownloadInterview($ai);

        list($firstManager, $secondManager, $managerFunc) = $userService->getAllManagersByEmployee($ai->getEmployee());
        $prevAi = $this->em()->getRepository(AnnualInterview::class)->getPrevAnnualInterview($ai);

        $html = $this->renderView('interviews/pdf/interview_annual.html.twig', [
            'managerFunc' => $managerFunc,
            'secondManager' => $secondManager,
            'ai' => $ai,
            'prevAi' => $prevAi
        ]);

        $options = [
            'footer-html' => $this->renderView('interviews/pdf/footer/footer_pdf.html.twig'),
            'header-html' => $this->renderView('interviews/pdf/header/header_annual_pdf.html.twig', ['ai' => $ai]),
            'margin-top' => 25,
        ];

        $pdf = $pdfService->generatePdf($html, $options);
        $name = 'Entretien annuel ' . $ai->getCreatedAt()->format('Y') . ' - ' . $ai->getEmployee()->getFullName() . '.pdf';

        return $this->responseFile($pdf, $name, 'application/pdf');
    }

    /**
     * @param $interview
     */
    private function checkAccessDownloadInterview($interview)
    {
        if (!$interview) {
            throw $this->createNotFoundException('Unable to find this entity.');
        }

        if (!$interview->getOwnInterviewValidated()) {
            throw new AccessDeniedHttpException('This entity is not yet validated.');
        }

        if (!$interview->getInterviewValidated()) {
            throw new AccessDeniedHttpException('This entity is not yet validated.');
        }

        $usersAllowed = $this->em()->getRepository(User::class)->getManagersIdByUser($interview->getEmployee());
        if (!in_array($this->getUser()->getId(), $usersAllowed) && !$this->getUser()->isAdmin()
            && $this->getUser()->getId() !== $interview->getEmployee()->getId()) {
            throw new AccessDeniedHttpException('Access denied');
        }
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
            throw new AccessDeniedHttpException('This entity is not yet validated.');
        }

        if (!$interview->getInterviewValidated()) {
            throw new AccessDeniedHttpException('This entity is not yet validated.');
        }

        $usersAllowed = $this->em()->getRepository(User::class)->getManagersIdByUser($interview->getEmployee());
        if (!in_array($this->getUser()->getId(), $usersAllowed) && !$this->getUser()->isAdmin()) {
            throw new AccessDeniedHttpException('Access denied');
        }
    }

    /**
     * @param $content
     * @param $fileName
     * @param $fileType
     * @return Response
     */
    protected function responseFile(&$content, $fileName, $fileType)
    {
        return new Response(
            $content,
            200,
            [
                'Cache-Control' => 'private',
                'Content-Type' => $fileType,
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ]
        );
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    private function em()
    {
        return $this->getDoctrine()->getManager();
    }
}
