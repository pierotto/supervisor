<?php declare(strict_types = 1);

namespace Pierotto\Tests;

class ProgramTest extends \PHPUnit\Framework\TestCase
{

	/**
	 * @dataProvider configurationDataProvider
	 */
	public function testProgramToArray(
		array $configuration,
		array $expected
	): void
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
		);

		$this->assertEquals($expected, $program->toArray());
	}


	public static function configurationDataProvider(): array
	{
		return [
			[
				'configuration' => [
					'command' => 'my_command',
					'numprocs' => 2,
					'autostart' => TRUE,
					'autorestart' => FALSE,
					'killasgroup' => TRUE,
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
					'autostart' => TRUE,
					'autorestart' => FALSE,
					'killasgroup' => TRUE,
					'startretries' => 3,
					'user' => 'my_user',
					'directory' => '/path/to/directory',
					'stdout_logfile' => 'stdout.log',
					'stderr_logfile' => 'stderr.log',
					'environment' => 'production',
					'stopsignal' => 'SIGTERM',
					'stopwaitsecs' => 10,
					'priority' => 5,
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
				],
			],
		];
	}

}
