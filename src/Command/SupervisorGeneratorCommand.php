<?php declare(strict_types = 1);

namespace Pierotto\SupervisorBundle\Command;

class SupervisorGeneratorCommand extends \Symfony\Component\Console\Command\Command
{

	public function __construct(
		string $name,
		private readonly array $programs,
		private readonly string $path
	)
	{
		parent::__construct($name);
	}


	protected function configure(): void
	{
		$this->setDescription('Creates a supervisor.conf file in app/config');
	}


	public function execute(
		\Symfony\Component\Console\Input\InputInterface $input,
		\Symfony\Component\Console\Output\OutputInterface $output
	): int
	{
		$filesystem = new \Symfony\Component\Filesystem\Filesystem();
		$filesystem->dumpFile($this->path, $this->create());

		return 0;
	}


	private function create(): string
	{
		$template = <<<EOT
		[program:%s]
		command = %s
		numprocs = %d
		autostart = %s
		autorestart = %s
		process_name = %%(program_name)s_%%(process_num)02d
		killasgroup = %s
		startretries = %d


		EOT;

		$content = '';
		foreach ($this->programs as $name => $program) {
			$autostart = $program['autostart'] ? 'true' : 'false';
			$autorestart = $program['autorestart'] ? 'true' : 'false';
			$killasgroup = $program['killasgroup'] ? 'true' : 'false';
			$content .= \sprintf($template, $name, $program['command'], $program['numprocs'], $autostart, $autorestart, $killasgroup, $program['startretries']);
		}

		return $content;
	}

}
