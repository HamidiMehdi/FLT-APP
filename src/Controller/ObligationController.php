<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ObligationController extends AbstractController
{
    /**
     * @Route("/cgu/check", name="cgu_ckeck")
     */
    public function cguCheck()
    {
        $response = ['cguAccepted' => true];

        if ($this->getUser()->getCguAccepted() === null) {
            $response['cguAccepted'] = false;
            $response['cguTemplate'] = $this->renderView('cgu/cgu_popup.html.twig');

        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/cgu/accepted", name="cgu_accepted")
     */
    public function cguAccepted()
    {
        $this->getUser()->setCguAccepted(new \DateTime());
        $this->em()->persist($this->getUser());
        $this->em()->flush();

        return new JsonResponse();
    }

    /**
     * @Route("/vie-privee", name="vie_privee")
     */
    public function viePrivee()
    {
        return $this->render('vie_privee/vie_privee.html.twig');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    private function em()
    {
        return $this->getDoctrine()->getManager();
    }
}
