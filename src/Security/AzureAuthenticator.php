<?php

namespace App\Security;

use App\Entity\User;
use App\Service\LoggerAuth;
use App\Service\MicrosoftGraphService;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class AzureAuthenticator
 * @package App\Security
 */
class AzureAuthenticator extends SocialAuthenticator
{
    /** @var ClientRegistry $clientRegistry */
    private $clientRegistry;

    /** @var EntityManagerInterface $em */
    private $em;

    /** @var RouterInterface $router */
    private $router;

    /** @var LoggerAuth $logger */
    private $logger;

    /** @var MicrosoftGraphService $graphService */
    private $graphService;

    /**
     * AzureAuthenticator constructor.
     * @param ClientRegistry $clientRegistry
     * @param EntityManagerInterface $em
     * @param RouterInterface $router
     * @param LoggerAuth $logger
     * @param MicrosoftGraphService $graphService
     */
    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router, LoggerAuth $logger, MicrosoftGraphService $graphService)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->logger = $logger;
        $this->graphService = $graphService;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        return $request->getPathInfo() == '/connect/azure/check' && $request->isMethod('GET');
    }

    /**
     * @param Request $request
     * @return \League\OAuth2\Client\Token\AccessToken|mixed
     */
    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getAzureClient());
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return User|object|\Symfony\Component\Security\Core\User\UserInterface|null
     * @throws \Exception
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $azureUser = $this->getAzureClient()->fetchUserFromToken($credentials);
        $azureData = $azureUser->toArray();

        if (!array_key_exists('unique_name', $azureData)) {
            throw new \Exception('key "unique_name" not found from azure data');
        } else if (!array_key_exists('given_name', $azureData)) {
            throw new \Exception('key "given_name" not found from azure data');
        } else if (!array_key_exists('family_name', $azureData)) {
            throw new \Exception('key "family_name" not found from azure data');
        } else if (!array_key_exists('oid', $azureData)) {
            throw new \Exception('key "oid" not found from azure data');
        }

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $azureData['unique_name']]);
        if (!$user) {
            $user = new User();
            $user->setRoles(['ROLE_USER']);
            $user->setFirstName($azureData['given_name']);
            $user->setLastName($azureData['family_name']);
            $user->setEmail($azureData['unique_name']);
            $user->setBirthday(null);
            $user->setDateEntered(null);
            $user->setAzureId($azureData['oid']);
            $user->setLocation($this->graphService->getLocalisationUser($credentials->getToken()));
        }

        $user->setAzureAccessToken($credentials->getToken());
        $user->setLastConnection(new \DateTime('now'));

        // add log
        $this->logger->loggerAuth($azureData['given_name'],$azureData['family_name'], $azureData['unique_name']);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @return \KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface
     */
    private function getAzureClient()
    {
        return $this->clientRegistry->getClient('azure');
    }

    /**
     * Returns a response that directs the user to authenticate.
     *
     * This is called when an anonymous request accesses a resource that
     * requires authentication. The job of this method is to return some
     * response that "helps" the user start into the authentication process.
     *
     * Examples:
     *  A) For a form login, you might redirect to the login page
     *      return new RedirectResponse('/login');
     *  B) For an API token authentication system, you return a 401 response
     *      return new Response('Auth header required', 401);
     *
     * @param Request $request The request that resulted in an AuthenticationException
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $authException The exception that started the authentication process
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function start(Request $request, \Symfony\Component\Security\Core\Exception\AuthenticationException $authException = null)
    {
        return new RedirectResponse('/login');
    }

    /**
     * Called when authentication executed, but failed (e.g. wrong username password).
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the login page or a 403 response.
     *
     * If you return null, the request will continue, but the user will
     * not be authenticated. This is probably not what you want to do.
     *
     * @param Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function onAuthenticationFailure(Request $request, \Symfony\Component\Security\Core\Exception\AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
        return new RedirectResponse('/login');
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     *
     * @param Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param string $providerKey The provider (i.e. firewall) key
     *
     * @return void
     */
    /**
     * @param Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param string $providerKey
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response|null
     */
    public function onAuthenticationSuccess(Request $request, \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token, $providerKey)
    {
        /**
         * Voir si on peut recup le token pour pouvoir deco le mec a tout moment
         *
         * $user = $token->getUser();
         * $azureApiToken = $this->fetchAccessToken($this->getAzureClient());
         * $user->setApiToken($googleApiToken);
         * $this->documentManager->persist($user);
         * $this->documentManager->flush();
         */

        return new RedirectResponse('/dashboard');
        // TODO: Implement onAuthenticationSuccess() method.
    }
}