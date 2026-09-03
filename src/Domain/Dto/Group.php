<?php

declare(strict_types=1);

namespace Pierotto\SupervisorBundle\Domain\Dto;

class Group
{
    /**
     * @param list<string> $programs
     */
    public function __construct(
        private readonly array $programs,
    ) {
    }

    /**
     * @return non-empty-list<non-falsy-string>
     */
    public function toArray(): array
    {
        return [\sprintf('programs = %s', \implode(',', $this->programs))];
    }
}
