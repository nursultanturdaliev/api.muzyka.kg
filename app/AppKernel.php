<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
	public function registerBundles()
	{
		$bundles = array(
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\SecurityBundle\SecurityBundle(),
			new Symfony\Bundle\TwigBundle\TwigBundle(),
			new Symfony\Bundle\MonologBundle\MonologBundle(),
			new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
			new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
			new AppBundle\AppBundle(),
			new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
			new JMS\SerializerBundle\JMSSerializerBundle(),
			new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
			new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
			new LyricsBundle\LyricsBundle(),
			new RCH\JWTUserBundle\RCHJWTUserBundle(),
			new FOS\UserBundle\FOSUserBundle(),
			new Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle(),
			new Gesdinet\JWTRefreshTokenBundle\GesdinetJWTRefreshTokenBundle(),
			new Nelmio\CorsBundle\NelmioCorsBundle(),
			new Snc\RedisBundle\SncRedisBundle(),
			new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),

            new Application\Sonata\AdminBundle\ApplicationSonataAdminBundle(),

            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),


		);

		if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
			$bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
			$bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
			$bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();

			if ('dev' === $this->getEnvironment()) {
				$bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
				$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
				$bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
			}
		}

		return $bundles;
	}

	public function registerContainerConfiguration(LoaderInterface $loader)
	{
		$loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
	}
}
