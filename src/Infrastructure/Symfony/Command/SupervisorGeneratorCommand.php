<?php

declare(strict_types=1);

namespace Pierotto\SupervisorBundle\Infrastructure\Symfony\Command;

use Pierotto\SupervisorBundle\Domain\Supervisor\SupervisorConfigGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(name: 'supervisor:generate', description: 'Generates configuration for Supervisor.')]
class SupervisorGeneratorCommand extends Command
{
    public function __construct(
        private readonly SupervisorConfigGenerator $supervisorConfigGenerator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('path', InputArgument::REQUIRED, 'The name of the file with the full path.');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = (string) $input->getArgument('path');
        if ('' === $path) {
            $output->writeln('<error>The specified file path is empty.</error>');

            return self::FAILURE;
        }

        if (!\is_writable(\dirname($path))) {
            $output->writeln('<error>Target directory is not writable.</error>');

            return self::FAILURE;
        }

        $filesystem = new Filesystem();
        $filesystem->dumpFile($path, $this->supervisorConfigGenerator->generate());

        $output->writeln(\sprintf('Configuration generation to file [%s] has been completed.', $path));

        return self::SUCCESS;
    }
}
