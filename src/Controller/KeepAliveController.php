<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KeepAliveController extends AbstractController
{
    /**
     * @Route("/keepalive", name="keep_alive")
     */
    public function index()
    {
        return new Response($this->getUser() === null ? 'logged out' : 'logged in');
    }
}
