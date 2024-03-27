<?php declare(strict_types = 1);

namespace Pierotto\SupervisorBundle\Domain;

class Program
{

	public function __construct(
		private readonly string $command,
		private readonly int $numprocs,
		private readonly bool $autostart,
		private readonly bool $autorestart,
		private readonly bool $killasgroup,
		private readonly int $startretries,
		private readonly ?string $user,
		private readonly ?string $directory,
		private readonly ?string $stdout_logfile,
		private readonly ?string $stderr_logfile,
		private readonly ?string $environment,
		private readonly ?string $stopsignal,
		private readonly ?int $stopwaitsecs,
		private readonly ?int $priority,
		private readonly ?int $startsecs,
	)
	{
	}


	public function toArray(): array
	{
		$options = [
			'command' => $this->command,
			'numprocs' => $this->numprocs,
			'autostart' => $this->autostart ? 'true' : 'false',
			'autorestart' => $this->autorestart ? 'true' : 'false',
			'killasgroup' => $this->killasgroup ? 'true' : 'false',
			'startretries' => $this->startretries,
			'user' => $this->user,
			'directory' => $this->directory,
			'stdout_logfile' => $this->stdout_logfile,
			'stderr_logfile' => $this->stderr_logfile,
			'environment' => $this->environment,
			'stopsignal' => $this->stopsignal,
			'stopwaitsecs' => $this->stopwaitsecs,
			'priority' => $this->priority,
			'startsecs' => $this->startsecs,
		];

		$options = \array_filter($options, static fn($value) => $value !== NULL);

		return \array_map(static fn($key, $value) => "$key = $value", \array_keys($options), $options);
	}

}
