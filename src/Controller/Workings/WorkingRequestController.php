<?php

namespace App\Controller\Workings;

use App\Entity\User;
use App\Entity\Working;
use App\Enum\PaginationEnum;
use App\Enum\WorkingEnum;
use App\Form\Workings\WorkingRequestAnswerType;
use App\Service\WorkingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teletravail")
 */
class WorkingRequestController extends AbstractController
{
    /**
     * @Route("/liste/demande/collaborateur", name="working_request_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function workingRequestList()
    {
        $working = $this->getWorkingRequestByManager($this->getUser(), null, WorkingEnum::PENDING_STATUS);
        $workingData = [
            'data' => $working,
            'pagination' => [
                'pages' => count($working) > 0 ? ceil(count($working) / PaginationEnum::DEFAULT_NUMBER_ELEMENT) : 1,
                'current_page' => 1
            ]
        ];

        return $this->render('workings/working_request/working_request_list.html.twig', [
            'workingData' => $workingData
        ]);
    }

    /**
     * @Route("/liste/demande/collaborateur/recherche", name="working_request_search")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getDataWorkingAjax(Request $request)
    {
        $search = $request->get('search');
        $page = $request->get('page');
        $dateFilter = $request->get('date_filter');
        $status = $request->get('status');
        $offset = $page === 1 ? 0 : PaginationEnum::DEFAULT_NUMBER_ELEMENT * ($page - 1);

        $data = $this->getWorkingRequestByManager($this->getUser(), $dateFilter, $status, $search, $offset);

        return new JsonResponse(
            [
                'html' => $this->renderView('workings/working_request/partial/working_request_item.html.twig', [
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
     * @param User $user
     * @param $dateFilter
     * @param $status
     * @param null $search
     * @param int $offset
     * @return mixed
     */
    private function getWorkingRequestByManager(User $user, $dateFilter, $status, $search = null, $offset = 0)
    {
        return $this->em()->getRepository(Working::class)->getWorkingByManager($user, $dateFilter, $status, $search, $offset);
    }

    /**
     * @Route("/collaborateur/{id}", name="working_request_show")
     * @param Request $request
     * @param $id
     * @param WorkingService $workingService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function validatedWorkingRequest(Request $request, $id, WorkingService $workingService)
    {
        /** @var Working $working */
        $working = $this->em()->getRepository(Working::class)->find($id);
        $this->checkAccessWorking($working);

        $form = $this->createForm(WorkingRequestAnswerType::class, $working);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $working->setDecidedBy($this->getUser());
            $working->setDecision(new \DateTime('now'));

            if (!$working->getIsAccepted()) {
                $working->setReportRequest(false);
            }

            $this->em()->persist($working);
            $this->em()->flush();

            $workingService->sendChoiceWorkingRequest($working);

            return $this->redirectToRoute('working_request_list');
        }

        return $this->render('workings/working_request/validated_working_request.html.twig', [
            'working' => $working,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Working $working
     */
    private function checkAccessWorking(Working $working)
    {
        if (!$working) {
            throw $this->createNotFoundException('Unable to find this entity.');
        }

        if ($working->getDecidedBy() || $working->getDecision()) {
            throw new AccessDeniedHttpException('Access denied');
        }

        $employees = $this->em()->getRepository(User::class)->getEmployeeIdManagedByUser($this->getUser());
        if ($this->getUser()->getId() === $working->getEmployee()->getId() || !in_array($working->getEmployee()->getId(), $employees)) {
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