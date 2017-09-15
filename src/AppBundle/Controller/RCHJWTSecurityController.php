<?php

namespace AppBundle\Controller;

use RCH\JWTUserBundle\Controller\SecurityController as BaseController;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class RCHJWTSecurityController extends BaseController
{
    /**
     * Generates a JWT from given User.
     *
     * @param UserInterface $user
     * @param int $statusCode
     *
     * @return array Response body containing the User and its tokens
     */
    protected function renderToken(UserInterface $user, $statusCode = 200)
    {
        $body = [
            'token'         => $this->container->get('lexik_jwt_authentication.jwt_manager')->create($user),
            'refresh_token' => $this->attachRefreshToken($user),
            'user'          => $user->getUsername(),
            'first_name'    => $user->getFirstName(),
            'last_name'     => $user->getLastName(),
            'photo'         => $user->getPhoto()
        ];

        return new JsonResponse($body, $statusCode);
    }
}