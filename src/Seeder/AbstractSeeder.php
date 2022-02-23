<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

abstract class AbstractSeeder implements SeederInterface
{
    /** @psalm-var positive-int */
    protected int $priority = 1;

    public abstract function run(): \Generator;

    /**
     * @psalm-param ?positive-int $priority
     */
    public function __construct(?int $priority = null)
    {
        if ($priority) {
            $this->priority = $priority;
        }
    }

    /**
     * @psalm-param positive-int $priority
     */
    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @psalm-return positive-int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
