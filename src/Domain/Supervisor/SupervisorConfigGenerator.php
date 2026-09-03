<?php

declare(strict_types=1);

namespace Pierotto\SupervisorBundle\Domain\Supervisor;

use Pierotto\SupervisorBundle\Domain\Dto\Group;
use Pierotto\SupervisorBundle\Domain\Dto\Program;

class SupervisorConfigGenerator
{
    private const PROGRAM_TEMPLATE = <<<EOT
	[program:%s]
	process_name = %%(program_name)s_%%(process_num)02d
	%s

	EOT;

    private const GROUP_TEMPLATE = <<<EOT
	[group:%s]
	%s

	EOT;

    /**
     * @param array<string, array{
     *     command: string,
     *     numprocs: int,
     *     autostart: bool,
     *     autorestart: bool,
     *     killasgroup: bool,
     *     startretries: int,
     *     user?: string,
     *     directory?: string,
     *     stdout_logfile?: string,
     *     stderr_logfile?: string,
     *     environment?: string,
     *     stopsignal?: string,
     *     stopwaitsecs?: int,
     *     priority?: int,
     *     startsecs?: int
     * }> $programs
     */
    public function __construct(
        private readonly string $prefix,
        private readonly array $programs,
        private readonly string $group = '',
    ) {
    }

    public function generate(): string
    {
        $content = [];
        foreach ($this->programs as $name => $program) {
            $content[] = $this->generateProgram($name, $program);
        }

        if ('' !== $this->group && [] !== $this->programs) {
            $content[] = $this->generateGroup();
        }

        return \implode(\PHP_EOL, $content);
    }

    /**
     * @param array{
     *      command: string,
     *      numprocs: int,
     *      autostart: bool,
     *      autorestart: bool,
     *      killasgroup: bool,
     *      startretries: int,
     *      user?: string,
     *      directory?: string,
     *      stdout_logfile?: string,
     *      stderr_logfile?: string,
     *      environment?: string,
     *      stopsignal?: string,
     *      stopwaitsecs?: int,
     *      priority?: int,
     *      startsecs?: int
     *  } $configuration
     */
    private function generateProgram(string $name, array $configuration): string
    {
        $program = new Program(
            command: $configuration['command'],
            numprocs: $configuration['numprocs'],
            autostart: $configuration['autostart'],
            autorestart: $configuration['autorestart'],
            killasgroup: $configuration['killasgroup'],
            startretries: $configuration['startretries'],
            user: $configuration['user'] ?? null,
            directory: $configuration['directory'] ?? null,
            stdout_logfile: $configuration['stdout_logfile'] ?? null,
            stderr_logfile: $configuration['stderr_logfile'] ?? null,
            environment: $configuration['environment'] ?? null,
            stopsignal: $configuration['stopsignal'] ?? null,
            stopwaitsecs: $configuration['stopwaitsecs'] ?? null,
            priority: $configuration['priority'] ?? null,
            startsecs: $configuration['startsecs'] ?? null,
        );

        return \sprintf(
            self::PROGRAM_TEMPLATE,
            $this->getPrefixedName($name),
            \implode(\PHP_EOL, $program->toArray()),
        );
    }

    private function generateGroup(): string
    {
        $names = \array_map(
            fn (string $name): string => $this->getPrefixedName($name),
            \array_keys($this->programs),
        );

        $group = new Group($names);

        return \sprintf(
            self::GROUP_TEMPLATE,
            $this->group,
            \implode(\PHP_EOL, $group->toArray()),
        );
    }

    private function getPrefixedName(string $name): string
    {
        return '' === $this->prefix ? $name : \sprintf('%s_%s', $this->prefix, $name);
    }
}
