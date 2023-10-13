<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

class Locator
{
    /**@var LocatorInterface[] */
    private array $locator;

    public function __construct(
        LocatorInterface ...$locator,
    ) {
        $this->locator = $locator;
    }

    /**
     * @return SeederInterface[]
     */
    public function findSeeders(?string $seeder = null): array
    {
        $seeders = [];

        foreach ($this->locator as $locator) {
            $seeders = \array_merge($seeders, $locator->find());
        }

        if ($seeder !== null) {
            $seeders = \array_filter(
                $seeders,
                static fn(SeederInterface $s): bool => (new \ReflectionClass($s))->getShortName() === $seeder,
            );
        }

        \usort($seeders, static function (SeederInterface $a, SeederInterface $b) {
            if ($a->getPriority() === $b->getPriority()) {
                return 0;
            }

            return ($a->getPriority() < $b->getPriority()) ? -1 : 1;
        });

        return $seeders;
    }

    public static function isSeederClass(\ReflectionClass $class): bool
    {
        return !$class->isAbstract() && !$class->isInterface() && $class->implementsInterface(SeederInterface::class);
    }
}
