<?php declare(strict_types = 1);

namespace Pierotto\SupervisorBundle\DependencyInjection;

class Configuration implements \Symfony\Component\Config\Definition\ConfigurationInterface
{

	public function getConfigTreeBuilder(): \Symfony\Component\Config\Definition\Builder\TreeBuilder
	{
		$treeBuilder = new \Symfony\Component\Config\Definition\Builder\TreeBuilder('supervisor');

		$treeBuilder->getRootNode()
			->fixXmlConfig('program')
				->children()
					->scalarNode('prefix')->defaultValue('')->end()
					->arrayNode('programs')
					->useAttributeAsKey('name')
					->arrayPrototype()
						->children()
							->scalarNode('command')->isRequired()->end()
							->integerNode('numprocs')->defaultValue(1)->end()
							->booleanNode('autostart')->defaultValue(TRUE)->end()
							->booleanNode('autorestart')->defaultValue(TRUE)->end()
							->booleanNode('killasgroup')->defaultValue(TRUE)->end()
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
			->end()
		;

		return $treeBuilder;
	}

}
