<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Spiral\Core\FactoryInterface;
use Spiral\Files\FilesInterface;
use Spiral\Tokenizer\Reflection\ReflectionFile;

final class DirectoryLocator implements LocatorInterface
{
    public function __construct(
        private readonly FilesInterface $files,
        private readonly DatabaseSeederConfig $config,
        private readonly FactoryInterface $factory,
    ) {
    }

    public function find(): array
    {
        if (!$this->files->isDirectory($this->config->getSeedersDirectory())) {
            return [];
        }

        $seeders = [];
        foreach ($this->files->getFiles($this->config->getSeedersDirectory(), '*.php') as $filename) {
            $reflection = new ReflectionFile($filename);
            $classes = $reflection->getClasses();

            if ($classes === [] || !Locator::isSeederClass(new \ReflectionClass($classes[0]))) {
                continue;
            }

            $seeders[$classes[0]] = $this->factory->make($classes[0]);
        }

        return $seeders;
    }
}
