<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

use Spiral\Attributes\ReaderInterface;
use Spiral\DatabaseSeeder\Attribute\Seeder;
use Spiral\Core\FactoryInterface;
use Spiral\Tokenizer\ClassesInterface;

class AttributeLocator
{
    public function __construct(
        private readonly ClassesInterface $classesLocator,
        private readonly ReaderInterface $reader,
        private readonly FactoryInterface $factory
    ) {
    }

    /**
     * @return SeederInterface[]
     */
    public function find(): array
    {
        $seeders = [];
        foreach ($this->classesLocator->getClasses() as $class) {
            if (!Locator::isSeederClass($class)) {
                continue;
            }

            if ($attr = $this->reader->firstClassMetadata($class, Seeder::class)) {
                $seeders[] = $this->factory->make($class->getName(), ['priority' => $attr->priority]);
            }
        }

        return $seeders;
    }
}
