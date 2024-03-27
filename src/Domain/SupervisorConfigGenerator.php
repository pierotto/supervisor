<?php declare(strict_types = 1);

namespace Pierotto\SupervisorBundle\Domain;

class SupervisorConfigGenerator
{

	private const TEMPLATE = <<<EOT
	[program:%s]
	process_name = %%(program_name)s_%%(process_num)02d
	%s

	EOT;


	public function __construct(
		private readonly string $prefix,
		private readonly array $programs,
	)
	{
	}


	public function generate(): string
	{
		$content = [];
		foreach ($this->programs as $name => $program) {
			$content[] = $this->generateProgram($name, $program);
		}

		return \implode(\PHP_EOL, $content);
	}


	private function generateProgram(
		string $name,
		array $configuration
	): string
	{
		$program = new \Pierotto\SupervisorBundle\Domain\Program(
			$configuration['command'],
			$configuration['numprocs'],
			$configuration['autostart'],
			$configuration['autorestart'],
			$configuration['killasgroup'],
			$configuration['startretries'],
			$configuration['user'] ?? NULL,
			$configuration['directory'] ?? NULL,
			$configuration['stdout_logfile'] ?? NULL,
			$configuration['stderr_logfile'] ?? NULL,
			$configuration['environment'] ?? NULL,
			$configuration['stopsignal'] ?? NULL,
			$configuration['stopwaitsecs'] ?? NULL,
			$configuration['priority'] ?? NULL,
			$configuration['startsecs'] ?? NULL,
		);

		return \sprintf(
			self::TEMPLATE,
			$this->getPrefixedName($name),
			\implode(\PHP_EOL, $program->toArray())
		);
	}


	private function getPrefixedName(string $name): string
	{
		return $this->prefix === '' ? $name : \sprintf('%s_%s', $this->prefix, $name);
	}

}
