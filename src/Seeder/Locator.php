<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

class Locator
{
    public function __construct(
        private AttributeLocator $attributeLocator,
        private DirectoryLocator $directoryLocator
    ) {
    }

    /**
     * @return SeederInterface[]
     */
    public function findSeeders(?string $seeder = null): array
    {
        $seeders = \array_replace($this->directoryLocator->find(), $this->attributeLocator->find());

        if ($seeder) {
            $seeders = \array_filter(
                $seeders,
                fn(SeederInterface $seeder) => (new \ReflectionClass($seeder))->getShortName() === $seeder
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
