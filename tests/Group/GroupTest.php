<?php

declare(strict_types=1);

namespace Pierotto\SupervisorBundle\Tests\Group;

use PHPUnit\Framework\TestCase;
use Pierotto\SupervisorBundle\Domain\Dto\Group;

class GroupTest extends TestCase
{
    public function testGroupJoinsProgramNames(): void
    {
        $group = new Group(['first_program', 'second_program', 'third_program']);

        $this->assertEquals(
            ['programs = first_program,second_program,third_program'],
            $group->toArray(),
        );
    }

    public function testGroupWithSingleProgram(): void
    {
        $group = new Group(['only_program']);

        $this->assertEquals(['programs = only_program'], $group->toArray());
    }
}
