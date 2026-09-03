<?php

declare(strict_types=1);

namespace Pierotto\SupervisorBundle\Tests\Supervisor;

use PHPUnit\Framework\TestCase;
use Pierotto\SupervisorBundle\Domain\Supervisor\SupervisorConfigGenerator;

class SupervisorConfigGeneratorTest extends TestCase
{
    /**
     * @return array<string, array{
     *     command: string,
     *     numprocs: int,
     *     autostart: bool,
     *     autorestart: bool,
     *     killasgroup: bool,
     *     startretries: int
     * }>
     */
    private static function programs(): array
    {
        return [
            'worker' => [
                'command' => 'php bin/console app:worker',
                'numprocs' => 1,
                'autostart' => true,
                'autorestart' => true,
                'killasgroup' => true,
                'startretries' => 3,
            ],
            'scheduler' => [
                'command' => 'php bin/console app:scheduler',
                'numprocs' => 1,
                'autostart' => true,
                'autorestart' => true,
                'killasgroup' => true,
                'startretries' => 3,
            ],
        ];
    }

    public function testNoGroupSectionWhenGroupIsNotConfigured(): void
    {
        $generator = new SupervisorConfigGenerator('', self::programs());

        $config = $generator->generate();

        $this->assertStringContainsString('[program:worker]', $config);
        $this->assertStringContainsString('[program:scheduler]', $config);
        $this->assertStringNotContainsString('[group:', $config);
    }

    public function testGroupSectionListsEveryProgram(): void
    {
        $generator = new SupervisorConfigGenerator('', self::programs(), 'myapp');

        $config = $generator->generate();

        $this->assertStringContainsString("[group:myapp]\nprograms = worker,scheduler", $config);
    }

    public function testGroupSectionUsesPrefixedProgramNames(): void
    {
        $generator = new SupervisorConfigGenerator('myapp', self::programs(), 'myapp');

        $config = $generator->generate();

        $this->assertStringContainsString('[program:myapp_worker]', $config);
        $this->assertStringContainsString("[group:myapp]\nprograms = myapp_worker,myapp_scheduler", $config);
    }

    public function testNoGroupSectionWhenThereAreNoPrograms(): void
    {
        $generator = new SupervisorConfigGenerator('', [], 'myapp');

        $this->assertSame('', $generator->generate());
    }
}
