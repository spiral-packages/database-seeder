<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

use Spiral\Attributes\ReaderInterface;
use Spiral\DatabaseSeeder\Attribute\Seeder;
use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Spiral\Core\FactoryInterface;
use Spiral\Files\FilesInterface;
use Spiral\Tokenizer\ClassesInterface;
use Spiral\Tokenizer\Reflection\ReflectionFile;

class Locator
{
    public function __construct(
        private ClassesInterface $classesLocator,
        private ReaderInterface $reader,
        private FilesInterface $files,
        private DatabaseSeederConfig $config,
        private FactoryInterface $factory
    ) {
    }

    /**
     * @return SeederInterface[]
     */
    public function findSeeders(?string $seeder = null): array
    {
        $seeders = $this->findWithAttribute() + $this->findInDirectory();

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

    /**
     * @return SeederInterface[]
     */
    private function findWithAttribute(): array
    {
        $seeders = [];
        foreach ($this->classesLocator->getClasses() as $class) {
            if (!$this->isSeeder($class)) {
                continue;
            }

            if ($attr = $this->reader->firstClassMetadata($class, Seeder::class)) {
                $seeders[] = $this->factory->make($class->getName(), ['priority' => $attr->priority]);
            }
        }

        return $seeders;
    }

    /**
     * @return SeederInterface[]
     */
    private function findInDirectory(): array
    {
        if (!$this->files->isDirectory($this->config->getDirectory())) {
            return [];
        }

        $seeders = [];
        foreach ($this->files->getFiles($this->config->getDirectory(), '*.php') as $filename) {
            $reflection = new ReflectionFile($filename);
            $classes = $reflection->getClasses();
            if ($classes === [] || !$this->isSeeder($classes[0])) {
                continue;
            }

            $seeders[] = $this->factory->make($classes[0]->getName());
        }

        return $seeders;
    }

    private function isSeeder(\ReflectionClass $class): bool
    {
        return !$class->isAbstract() && !$class->isInterface() && $class->implementsInterface(SeederInterface::class);
    }
}
