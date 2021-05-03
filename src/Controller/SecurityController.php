<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @param ClientRegistry $clientRegistry
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(Request $request, ClientRegistry $clientRegistry, AuthenticationUtils $authenticationUtils)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('dashboard');
        }

        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $clientRegistry->getClient('azure')->redirect(
                [],
                [
                    'login_hint' => $form['email']->getData(),
                    'prompt' => 'login'
                ]
            );
        }

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_email' => $authenticationUtils->getLastUsername()
        ]);
    }

    /**
     * @Route("/logout_check", name="logout_check")
     */
    public function logout()
    {
        $url = 'https://login.microsoftonline.com/';
        $url .= $_ENV['AZURE_TENANT'] . '/oauth2/logout?post_logout_redirect_uri=';
        $url .= rawurlencode($this->generateUrl('logout', [], UrlGeneratorInterface::ABSOLUTE_URL));

        return $this->redirect($url);
    }
}
