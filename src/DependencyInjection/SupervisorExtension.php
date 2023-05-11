<?php declare(strict_types = 1);

namespace Pierotto\SupervisorBundle\DependencyInjection;

class SupervisorExtension extends \Symfony\Component\HttpKernel\DependencyInjection\Extension
{

	/**
	 * @throws \Exception
	 */
	public function load(
		array $configs,
		\Symfony\Component\DependencyInjection\ContainerBuilder $container
	): void
	{
		$loader = new \Symfony\Component\DependencyInjection\Loader\YamlFileLoader(
			$container,
			new \Symfony\Component\Config\FileLocator([__DIR__ . '/../Resources/config'])
		);
		$loader->load('services.yml');

		$configuration = new \Pierotto\SupervisorBundle\DependencyInjection\Configuration();
		$config = $this->processConfiguration($configuration, $configs);

		foreach ($config as $key => $value) {
			$container->setParameter(\sprintf('supervisor.%s', $key), $value);
		}
	}

}
