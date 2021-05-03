<?php

namespace App\Controller\Workings;

use App\Entity\User;
use App\Entity\Working;
use App\Enum\PaginationEnum;
use App\Enum\WorkingEnum;
use App\Form\Workings\NewWorkingType;
use App\Service\WorkingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teletravail")
 */
class WorkingController extends AbstractController
{
    /**
     * @Route("/liste", name="working_list")
     * @param Request $request
     * @param WorkingService $workingService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function workingList(Request $request, WorkingService $workingService)
    {
        $working = new Working();
        $form = $this->createForm(NewWorkingType::class, $working);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $working->setEmployee($this->getUser());
            $this->em()->persist($working);
            $this->em()->flush();

            $workingService->sendNewWorkingRequest($working);

            return $this->redirect($request->getUri());
        }

        $working = $this->getWorkingList($this->getUser(), null, WorkingEnum::PENDING_STATUS);
        $workingData = [
            'data' => $working,
            'pagination' => [
                'pages' => count($working) > 0 ? ceil(count($working) / PaginationEnum::DEFAULT_NUMBER_ELEMENT) : 1,
                'current_page' => 1
            ]
        ];

        return $this->render('workings/working_list/working_list.html.twig', [
            'form' => $form->createView(),
            'workingData' => $workingData
        ]);
    }

    /**
     * @Route("/liste/recherche", name="working_search")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getDataWorkingListAjax(Request $request)
    {
        $page = $request->get('page');
        $dateFilter = $request->get('date_filter');
        $status = $request->get('status');
        $offset = $page === 1 ? 0 : PaginationEnum::DEFAULT_NUMBER_ELEMENT * ($page - 1);

        $data = $this->getWorkingList($this->getUser(), $dateFilter, $status, $offset);

        return new JsonResponse(
            [
                'html' => $this->renderView('workings/working_list/partial/working_item.html.twig', [
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
     * @param int $offset
     * @return mixed
     */
    private function getWorkingList(User $user, $dateFilter, $status, $offset = 0)
    {
        return $this->em()->getRepository(Working::class)->getWorkingByUser($user, $dateFilter, $status, $offset);
    }

    /**
     * @Route("/demande/verification", name="add_working_check")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function checkWorkingInterval(Request $request)
    {
        $start = new \DateTime($request->get('start'));
        $end = new \DateTime($request->get('end'));
        $workingsCheck = $this->em()->getRepository(Working::class)->checkWorkingInterval($this->getUser(), $start, $end);

        return new JsonResponse([
            'periodExist' => count($workingsCheck) > 0,
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="delete_working")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteWorking($id)
    {
        /** @var Working $working */
        $working = $this->em()->getRepository(Working::class)->find($id);
        $this->checkAccessWorking($working);

        // Si demande toujours en attente
        if (!$working->getDecidedBy() && !$working->getDecision()) {
            $this->em()->remove($working);
            $this->em()->flush();
        }

        return $this->redirectToRoute('working_list');
    }

    /**
     * @param Working $working
     */
    private function checkAccessWorking(Working $working)
    {
        if (!$working) {
            throw $this->createNotFoundException('Unable to find this entity.');
        }

        if ($this->getUser()->getId() !== $working->getEmployee()->getId()) {
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