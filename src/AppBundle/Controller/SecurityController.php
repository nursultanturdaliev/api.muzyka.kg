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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
	public function loginFromOAuthResponseAction(Request $request, $provider)
	{
		$id             = $provider . 'Id';
		$userManager    = $this->get('doctrine.orm.default_entity_manager');
		$userRepository = $userManager->getRepository('AppBundle:User');

		$data = \GuzzleHttp\json_decode($request->getContent(), true);


		/** @var User $user */
		$user = $userRepository->findOneBy([$id => $data['id']]);

		if ($user) {
			if($provider == 'facebook' || $provider == 'google'){
                copy($data['photo'], 'uploads/users/' .  $data['id'] . '.jpg');
            }
			return $this->renderToken($user);
		}

		$user = $userRepository->findOneBy(['email' => $data['email']]);
		if ($user) {
			if ($provider == 'facebook') {
				copy($data['photo'], 'uploads/users/' .  $data['id'] . '.jpg');
				$user->setFacebookId($data['id']);
				$user->setFacebookAccessToken($data['accessToken']);
			} else if ($provider == 'google') {
                copy($data['photo'], 'uploads/users/' .  $data['id'] . '.jpg');
				$user->setGoogleAccessToken($data['accessToken']);
				$user->setGoogleId($data['id']);
			}
			return $this->renderToken($user);
		}

		$data['password'] = $this->generateRandomPassword();

		$user = new User();
		if ($provider == 'facebook') {
			$user->setFacebookId($data['id']);
			$user->setFacebookAccessToken($data['accessToken']);
            $user->setPhoto($data['id'] . '.jpg');
			copy($data['photo'], 'uploads/users/' .  $data['id'] . '.jpg');
		} else if ($provider == 'google') {
            copy($data['photo'], 'uploads/users/' .  $data['id'] . '.jpg');
			$user->setGoogleAccessToken($data['accessToken']);
			$user->setGoogleId($data['id']);
            $user->setPhoto($data['id'] . '.jpg');
		}

		$user->setPlainPassword($data['password']);
		$user->setFirstName($data['firstName']);
		$user->setLastName($data['lastName']);
		$user->setEmail($data['email']);
		$user->setUsername($data['email']);
		$userManager->persist($user);
		$userManager->flush();

		return $this->renderToken($user);
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
			'first_name'    => $user->getFirstName(),
			'last_name'     => $user->getLastName(),
			'photo'         => $user->getPhoto()
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