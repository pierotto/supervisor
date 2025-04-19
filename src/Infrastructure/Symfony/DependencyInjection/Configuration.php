<?php

declare(strict_types=1);

namespace Pierotto\SupervisorBundle\Infrastructure\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function __construct(
        private readonly string $alias,
    ) {
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder($this->alias);

        $treeBuilder->getRootNode() // @phpstan-ignore method.notFound
            ->fixXmlConfig('program')
                ->children()
                    ->scalarNode('prefix')->defaultValue('')->end()
                    ->arrayNode('programs')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('command')->isRequired()->end()
                            ->integerNode('numprocs')->defaultValue(1)->end()
                            ->booleanNode('autostart')->defaultValue(true)->end()
                            ->booleanNode('autorestart')->defaultValue(true)->end()
                            ->booleanNode('killasgroup')->defaultValue(true)->end()
                            ->integerNode('startretries')->defaultValue(3)->end()
                            ->scalarNode('user')->defaultNull()->end()
                            ->scalarNode('directory')->defaultNull()->end()
                            ->scalarNode('stdout_logfile')->defaultNull()->end()
                            ->scalarNode('stderr_logfile')->defaultNull()->end()
                            ->scalarNode('environment')->defaultNull()->end()
                            ->scalarNode('stopsignal')->defaultNull()->end()
                            ->integerNode('stopwaitsecs')->defaultNull()->end()
                            ->integerNode('priority')->defaultNull()->end()
                            ->integerNode('startsecs')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
