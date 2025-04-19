<?php

declare(strict_types=1);

namespace Pierotto\SupervisorBundle\Tests\Program;

use PHPUnit\Framework\TestCase;
use Pierotto\SupervisorBundle\Domain\Dto\Program;

class ProgramTest extends TestCase
{
    /**
     * @param array{
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
     * } $configuration
     * @param list<string> $expected
     *
     * @dataProvider configurationDataProvider
     */
    public function testProgramToArray(array $configuration, array $expected): void
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

        $this->assertEquals($expected, $program->toArray());
    }

    /**
     * @return list<array{
     *     configuration: array{
     *         command: string,
     *         numprocs: int,
     *         autostart: bool,
     *         autorestart: bool,
     *         killasgroup: bool,
     *         startretries: int,
     *         user?: string,
     *         directory?: string,
     *         stdout_logfile?: string,
     *         stderr_logfile?: string,
     *         environment?: string,
     *         stopsignal?: string,
     *         stopwaitsecs?: int,
     *         priority?: int,
     *         startsecs?: int
     *     },
     *     expected: list<string>
     * }>
     */
    public static function configurationDataProvider(): array
    {
        return [
            [
                'configuration' => [
                    'command' => 'my_command',
                    'numprocs' => 2,
                    'autostart' => true,
                    'autorestart' => false,
                    'killasgroup' => true,
                    'startretries' => 3,
                ],
                'expected' => [
                    'command = my_command',
                    'numprocs = 2',
                    'autostart = true',
                    'autorestart = false',
                    'killasgroup = true',
                    'startretries = 3',
                ],
            ],
            [
                'configuration' => [
                    'command' => 'my_command',
                    'numprocs' => 2,
                    'autostart' => true,
                    'autorestart' => false,
                    'killasgroup' => true,
                    'startretries' => 3,
                    'user' => 'my_user',
                    'directory' => '/path/to/directory',
                    'stdout_logfile' => 'stdout.log',
                    'stderr_logfile' => 'stderr.log',
                    'environment' => 'production',
                    'stopsignal' => 'SIGTERM',
                    'stopwaitsecs' => 10,
                    'priority' => 5,
                    'startsecs' => 0,
                ],
                'expected' => [
                    'command = my_command',
                    'numprocs = 2',
                    'autostart = true',
                    'autorestart = false',
                    'killasgroup = true',
                    'startretries = 3',
                    'user = my_user',
                    'directory = /path/to/directory',
                    'stdout_logfile = stdout.log',
                    'stderr_logfile = stderr.log',
                    'environment = production',
                    'stopsignal = SIGTERM',
                    'stopwaitsecs = 10',
                    'priority = 5',
                    'startsecs = 0',
                ],
            ],
        ];
    }
}
