<?php

namespace AppBundle\Security\Http\Authentication;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use FOS\UserBundle\Model\UserInterface;
/**
 * AuthenticationSuccessHandler
 *
 * @author Dev Lexik <dev@lexik.fr>
 */
class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var JWTManager
     */
    protected $jwtManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param JWTManager               $jwtManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(JWTManager $jwtManager, EventDispatcherInterface $dispatcher)
    {
        $this->jwtManager = $jwtManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {

        $user = $token->getUser();
        $jwt  = $this->jwtManager->create($user);

        $response = new JsonResponse();
        $event    = new AuthenticationSuccessEvent(['token' => $jwt], $user, $request, $response, false);

        $this->dispatcher->dispatch(Events::AUTHENTICATION_SUCCESS, $event);
        $response->setData($event->getData());

        $refresh_token = $event->getData()['refresh_token'];

        return $this->renderToken($user, $jwt, $refresh_token);
    }

    /**
     * Generates a JWT from given User.
     *
     * @param UserInterface $user
     * @param int $statusCode
     *
     * @return array Response body containing the User and its tokens
     */
    protected function renderToken(UserInterface $user, $token, $refresh_token, $statusCode = 200)
    {
        $body = [
            'token'         => $token,
            'refresh_token' => $refresh_token,
            'user'          => $user->getUsername(),
            'firstName'     => $user->getFirstName(),
            'lastName'      => $user->getLastName(),
            'photo'         => $user->getPhoto()
        ];

        return new JsonResponse($body, $statusCode);
    }

}
