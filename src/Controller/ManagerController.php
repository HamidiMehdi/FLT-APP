<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ManagerController
 * @package App\Controller
 * @Route("/manager")
 */
class ManagerController extends AbstractController
{
    /**
     * @Route("/liste", name="manager_list")
     */
    public function list()
    {
        return $this->render('manager/list.html.twig');




    }
}
