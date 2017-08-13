<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 8/13/17
 * Time: 12:24 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use RCH\JWTUserBundle\Exception\InvalidPropertyUserException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
	public function loginFromOAuthResponseAction(Request $request)
	{
		$userManager    = $this->get('doctrine.orm.default_entity_manager');
		$userRepository = $userManager->getRepository('AppBundle:User');

		$data = \GuzzleHttp\json_decode($request->getContent(), true);

		if (true !== $this->isValidFacebookAccount($data['facebookId'], $data['accessToken'])) {
			throw new InvalidPropertyUserException('The given facebook_id does not correspond to a valid acount');
		}
		/** @var User $user */
		$user = $userRepository->findOneBy(['facebookId' => $data['facebookId']]);

		if ($user) {
			return $this->renderToken($user);
		}

		$user = $userRepository->findOneBy(['email' => $data['email']]);

		if ($user) {
			$user->setFacebookId($data['facebookId']);
			$user->setFacebookAccessToken($data['accessToken']);
			return $this->renderToken($user);
		}

		$data['password'] = $this->generateRandomPassword();

		$user = new User();

		$user->setPlainPassword($data['password']);
		$user->setFirstName($data['name']);
		$user->setLastName($data['name']);
		$user->setFacebookId($data['facebookId']);
		$user->setFacebookAccessToken($data['accessToken']);
		$user->setEmail($data['email']);
		$user->setUsername($data['email']);
		$userManager->persist($user);
		$userManager->flush();

		return $this->renderToken($user);
	}

	/**
	 * @param $id
	 * @param $accessToken
	 *
	 * @return bool Facebook account status
	 * @throws InvalidPropertyUserException
	 * @internal param int $facebookId Facebook account id
	 * @internal param string $facebookAccessToken Facebook access token
	 *
	 */
	protected function isValidFacebookAccount($id, $accessToken)
	{
		$client = new \Goutte\Client();
		$client->request('GET', sprintf('https://graph.facebook.com/me?access_token=%s', $accessToken));
		$response = json_decode($client->getResponse()->getContent(), true);

		if (array_key_exists('error', $response)) {
			throw new InvalidPropertyUserException($response->error->message);
		}

		return $response['id'] == $id;
	}

	/**
	 * Generates a JWT from given User.
	 *
	 * @param User $user
	 * @param int $statusCode
	 *
	 * @return array Response body containing the User and its tokens
	 */
	protected function renderToken(User $user, $statusCode = 200)
	{
		$body = [
			'token'         => $this->container->get('lexik_jwt_authentication.jwt_manager')->create($user),
			'refresh_token' => $this->attachRefreshToken($user),
			'user'          => $user->getUsername(),
			'firstName'     => $user->getFirstName(),
			'lastName'      => $user->getLastName()
		];

		return new JsonResponse($body, $statusCode);
	}

	/**
	 * Generates a random password of 8 characters.
	 *
	 * @return string
	 */
	protected function generateRandomPassword()
	{
		$tokenGenerator = $this->container->get('fos_user.util.token_generator');

		return substr($tokenGenerator->generateToken(), 0, 8);
	}

	/**
	 * Provides a refresh token.
	 *
	 * @param User $user
	 *
	 * @return string The refresh Json Web Token.
	 */
	protected function attachRefreshToken(User $user)
	{
		$refreshTokenManager = $this->container->get('gesdinet.jwtrefreshtoken.refresh_token_manager');
		$refreshToken        = $refreshTokenManager->getLastFromUsername($user->getUsername());
		$refreshTokenTtl     = $this->container->getParameter('gesdinet_jwt_refresh_token.ttl');

		if (!$refreshToken instanceof RefreshToken) {
			$refreshToken   = $refreshTokenManager->create();
			$expirationDate = new \DateTime();
			$expirationDate->modify(sprintf('+%s seconds', $refreshTokenTtl));
			$refreshToken->setUsername($user->getUsername());
			$refreshToken->setRefreshToken();
			$refreshToken->setValid($expirationDate);

			$refreshTokenManager->save($refreshToken);
		}

		return $refreshToken->getRefreshToken();
	}
}