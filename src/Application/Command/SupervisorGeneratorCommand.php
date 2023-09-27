<?php declare(strict_types = 1);

namespace Pierotto\SupervisorBundle\Application\Command;

#[\Symfony\Component\Console\Attribute\AsCommand(name: 'supervisor:generate', description: 'Generates configuration for Supervisor.')]
class SupervisorGeneratorCommand extends \Symfony\Component\Console\Command\Command
{

	public function __construct(
		private readonly \Pierotto\SupervisorBundle\Domain\SupervisorConfigGenerator $supervisorConfigGenerator,
	)
	{
		parent::__construct();
	}


	protected function configure(): void
	{
		$this->addArgument('path', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'The name of the file with the full path.');
	}


	public function execute(
		\Symfony\Component\Console\Input\InputInterface $input,
		\Symfony\Component\Console\Output\OutputInterface $output
	): int
	{
		$path = (string) $input->getArgument('path');
		if ($path === '') {
			$output->writeln('The specified file path was not filled in.');

			return 1;
		}

		$filesystem = new \Symfony\Component\Filesystem\Filesystem();
		$filesystem->dumpFile($path, $this->supervisorConfigGenerator->generate());

		$output->writeln(\sprintf('Configuration generation to file %s has been completed.', $path));

		return 0;
	}

}
