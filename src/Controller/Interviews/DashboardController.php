<?php

namespace App\Controller\Interviews;

use App\Entity\AnnualInterview;
use App\Entity\History;
use App\Entity\Manager;
use App\Entity\User;
use App\Enum\HistoryEnum;
use App\Enum\PaginationEnum;
use App\Service\InterviewService;
use App\Service\UserService;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function dashboard()
    {
        if (!$this->getUser()->isAdmin()) {
            return $this->redirectToRoute('choice_interviews');
        }

        $dashboardUser = $this->getUsersDashboard();
        $dashboardUserData = [
            'data' => $dashboardUser,
            'pagination' => [
                'pages' => count($dashboardUser) > 0 ? ceil(count($dashboardUser) / PaginationEnum::DEFAULT_NUMBER_ELEMENT) : 1,
                'current_page' => 1,
            ]
        ];

        $usersCounter = $this->em()->getRepository(User::class)->getUsersCounter();
        $managersCounter = $this->em()->getRepository(Manager::class)->getManagersCounter();
        $adminsCounter = $this->em()->getRepository(User::class)->getAdminsCounter();
        $avgBilan = $this->em()->getRepository(AnnualInterview::class)->getAvgBilanByYear(date('Y'));

        /** @var Paginator $histories */
        $histories = $this->em()->getRepository(History::class)->getLatestHistoricalRelaunch();

        return $this->render('interviews/dashboard/dashboard.html.twig', [
            'collabsCounter' => $usersCounter,
            'managersCounter' => $managersCounter,
            'adminsCounter' => $adminsCounter,
            'avgBilan' => $avgBilan['avg'] == null ? 0 : $avgBilan['avg'],
            'dashboardUser' => $dashboardUserData,
            'histories' => $histories
        ]);
    }

    /**
     * @Route("/dashboard/users", name="dashboard_users_data")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function getUsersDashboardAjax(Request $request)
    {
        if (!$this->getUser()->isAdmin()) {
            return new JsonResponse(null,  Response::HTTP_FORBIDDEN);
        }

        $page = $request->get('page');
        $offset = $page === 1 ? 0 : PaginationEnum::DEFAULT_NUMBER_ELEMENT * ($page - 1);
        $search = $request->get('search');

        $data = $this->getUsersDashboard($offset, $search);

        return new JsonResponse(
            [
                'html' => $this->renderView('interviews/dashboard/partial/user.html.twig', [
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
     * @Route("/relaunch/user", name="relaunch_user")
     * @param Request $request
     * @param InterviewService $interviewService
     * @return JsonResponse
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function relaunchUser(Request $request, InterviewService $interviewService)
    {
        if (!$this->getUser()->isAdmin()) {
            throw new AccessDeniedHttpException('Access denied');
        }

        $interviewType = $request->get('interview');
        $userType = $request->get('user');
        $counter = $interviewService->relaunchUser($interviewType, $userType);
        $body = ['relaunch_counter' => $counter];

        if ($counter > 0) {
            $history = new History();
            $history->setUser($this->getUser());
            $history->setType(HistoryEnum::RELAUNCH_EMAIL);
            $history->setMessage([
                'relaunch_counter' => $counter,
                'user_type' => $userType,
                'interview_type' => $interviewType
            ]);

            $this->em()->persist($history);
            $this->em()->flush();
            $body['history'] = [
                'author' => $history->getUser()->getFullName(),
                'user_type' => $userType,
                'interview_type' => $interviewType,
                'date' => $history->getCreatedAt()->format('d/m/Y'),
            ];
        }

        return new JsonResponse($body);
    }

    /**
     * @param int $offset
     * @param null $search
     * @return mixed
     */
    private function getUsersDashboard($offset = 0, $search = null)
    {
        return $this->em()->getRepository(User::class)->getUsersDashboard($offset, $search);
    }

    /**
     * @Route("/analytics", name="dashboard_analytics")
     * @param UserService $userService
     * @return JsonResponse
     */
    public function getDataGraph(UserService $userService)
    {
        if (!$this->getUser()->isAdmin()) {
            return new JsonResponse(null,  Response::HTTP_FORBIDDEN);
        }

        $currentYear = date('Y');
        $dataProInterview = $userService->getDataGraphProByYear($currentYear);
        $dataAnnualInterview = $userService->getDataGraphAnnualByYear($currentYear);
        $dataAvgBilanAnnualInterview = $this->em()->getRepository(AnnualInterview::class)->getAnnualInterviewAvgByYear($currentYear);

        return new JsonResponse([
            'graphBarProInterview' => ['data' => $dataProInterview, 'max' => array_sum($dataProInterview)],
            'graphBarAnnualInterview' => ['data' => $dataAnnualInterview, 'max' => array_sum($dataAnnualInterview)],
            'graphPieAnnualInterview' => $dataAvgBilanAnnualInterview
        ]);
    }

    /**
     * @Route("/user/{id}/delete", name="delete_user")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUser($id)
    {
        if (!$this->getUser()->isAdmin()) {
            throw new  AccessDeniedHttpException('Access denied');
        }

        /** @var User $user */
        $user = $this->em()->getRepository(User::class)->find($id);
        if (!$user) {
            throw new NotFoundHttpException("User not found");
        }

        $history = new History();
        $history->setUser($this->getUser());
        $history->setType(HistoryEnum::DELETE_USER);
        $history->setMessage([
            'fullname' => $user->getFullName(),
            'email' => $user->getEmail()
        ]);

        $user = $user->anonymize();

        $this->em()->persist($user);
        $this->em()->persist($history);
        $this->em()->flush();
        $this->em()->remove($user);
        $this->em()->flush();

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    private function em()
    {
        return $this->getDoctrine()->getManager();
    }
}