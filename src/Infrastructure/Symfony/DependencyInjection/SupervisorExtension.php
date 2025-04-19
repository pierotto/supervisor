<?php

declare(strict_types=1);

namespace Pierotto\SupervisorBundle\Infrastructure\Symfony\DependencyInjection;

use Pierotto\SupervisorBundle\Domain\Supervisor\SupervisorConfigGenerator;
use Pierotto\SupervisorBundle\Infrastructure\Symfony\Command\SupervisorGeneratorCommand;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

class SupervisorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration($this->getAlias());
        $config = $this->processConfiguration($configuration, $configs);

        $container->register(SupervisorConfigGenerator::class)
            ->setArgument('$prefix', $config['prefix'])
            ->setArgument('$programs', $config['programs']);

        $container->register(SupervisorGeneratorCommand::class)
            ->setArgument('$supervisorConfigGenerator', new Reference(
                SupervisorConfigGenerator::class,
            ))
            ->addTag('console.command');
    }
}
