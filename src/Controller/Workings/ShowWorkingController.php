<?php

namespace App\Controller\Workings;

use App\Entity\User;
use App\Entity\Working;
use App\Service\WorkingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teletravail")
 */
class ShowWorkingController extends AbstractController
{
    /**
     * @Route("/demande/{id}/description", name="show_working")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showWorking($id)
    {
        $working = $this->em()->getRepository(Working::class)->find($id);
        $this->checkAccessShowWorking($working);

        return $this->render('workings/show_working/show_working.html.twig', [
            'working' => $working
        ]);
    }

    /**
     * @param Working $working
     */
    private function checkAccessShowWorking(Working $working)
    {
        if (!$working) {
            throw $this->createNotFoundException('Unable to find this entity.');
        }

        if (!$working->getDecidedBy() || !$working->getDecision()) {
            throw new AccessDeniedHttpException('Entity has not been validated');
        }

        $managers = $this->em()->getRepository(User::class)->getManagersIdByUser($working->getEmployee());
        if ($this->getUser()->getId() !== $working->getEmployee()->getId() && !in_array($this->getUser()->getId(), $managers)) {
            throw new AccessDeniedHttpException('Access denied');
        }
    }

    /**
     * @Route("/demande/{id}/compte/rendu", name="report_working")
     * @param Request $request
     * @param $id
     * @param WorkingService $workingService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function reportWorking(Request $request, $id, WorkingService $workingService)
    {
        /** @var Working $working */
        $working = $this->em()->getRepository(Working::class)->find($id);
        $this->checkAccessReportWorking($working);

        $form = $this->createFormBuilder()
            ->add('report', TextareaType::class, ['required' => false])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $working->setReport($form['report']->getData());
            $this->em()->persist($working);
            $this->em()->flush();

            $workingService->sendReportWorking($working);
            
            return $this->redirectToRoute('working_list');
        }

        return $this->render('workings/show_working/report_working.html.twig', [
            'form' => $form->createView(),
            'working' => $working
        ]);
    }

    /**
     * @param Working $working
     */
    private function checkAccessReportWorking(Working $working)
    {
        if (!$working) {
            throw $this->createNotFoundException('Unable to find this entity.');
        }

        if (!$working->getDecidedBy() || !$working->getDecision() || !$working->getIsAccepted() || !$working->getReportRequest()) {
            throw new AccessDeniedHttpException('This entity has not been validated or has been refused or report not requested');
        }

        if ($this->getUser()->getId() !== $working->getEmployee()->getId() && ($working->getReport() == null || $working->getReport() == '')) {
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