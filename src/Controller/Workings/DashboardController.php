<?php

namespace App\Controller\Workings;

use App\Form\Workings\filter\WorkingFilterType;
use App\Service\WorkingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teletravail")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="working_dashboard")
     * @param WorkingService $workingService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function showWorking(WorkingService $workingService)
    {
        if (!$this->getUser()->isAdmin()) {
            return $this->redirectToRoute('working_list');
        }

        $formFilter = $this->createForm(WorkingFilterType::class, ['em' => $this->em()]);

        $now = new \DateTime('now');
        $descriptionDay = $workingService->getWorkingDescriptionDay($now);

        return $this->render('workings/dashboard/dashboard.html.twig', [
            'formFilter' => $formFilter->createView(),
            'descriptionDay' => $descriptionDay,
            'date' => $now
        ]);
    }

    /**
     * @Route("/dashboard/graph", name="working_graph")
     * @param Request $request
     * @param WorkingService $workingService
     * @return JsonResponse
     * @throws \Exception
     */
    public function getDataGraph(Request $request, WorkingService $workingService)
    {
        $this->checkAccess();

        $user = $request->get('user');
        $month = $request->get('month');
        $location = $request->get('location');

        list($dataGraph, $axisX) = $workingService->getWorkingDataGraph($user, $month, $location);

        return new JsonResponse([
            'data' => $dataGraph,
            'axisX' => $axisX
        ]);
    }

    /**
     * @Route("/dashboard/jour/description", name="working_day_desc")
     * @param Request $request
     * @param WorkingService $workingService
     * @return JsonResponse
     * @throws \Exception
     */
    public function getDescWorkingDay(Request $request, WorkingService $workingService)
    {
        $this->checkAccess();

        $date = new \DateTime(addslashes($request->get('date')));
        $descriptionDay = $workingService->getWorkingDescriptionDay($date);

        return new JsonResponse([
            'html' => $this->renderView('workings/dashboard/partial/description_day.html.twig', [
                'descriptionDay' => $descriptionDay,
                'date' => $date
            ])
        ]);
    }

    /**
     * @Route("/dashboard/agenda", name="working_agenda")
     * @param Request $request
     * @param WorkingService $workingService
     * @return JsonResponse
     * @throws \Exception
     */
    public function getWorkingsByMonth(Request $request, WorkingService $workingService)
    {
        $this->checkAccess();
        $workings = $workingService->getWorkingsByMonth($request->get('month'), $request->get('year'));

        return new JsonResponse([
            'workings' => $workings
        ]);
    }

    private function checkAccess()
    {
        if (!$this->getUser()->isAdmin()) {
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