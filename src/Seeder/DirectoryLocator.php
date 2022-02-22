<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Seeder;

use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Spiral\Core\FactoryInterface;
use Spiral\Files\FilesInterface;
use Spiral\Tokenizer\Reflection\ReflectionFile;

class DirectoryLocator
{
    public function __construct(
        private FilesInterface $files,
        private DatabaseSeederConfig $config,
        private FactoryInterface $factory
    ) {
    }

    /**
     * @return SeederInterface[]
     */
    public function find(): array
    {
        if (!$this->files->isDirectory($this->config->getSeedersDirectory())) {
            return [];
        }

        $seeders = [];
        foreach ($this->files->getFiles($this->config->getSeedersDirectory(), '*.php') as $filename) {
            $reflection = new ReflectionFile($filename);
            $classes = $reflection->getClasses();
            if ($classes === [] || !Locator::isSeederClass($classes[0])) {
                continue;
            }

            $seeders[] = $this->factory->make($classes[0]->getName());
        }

        return $seeders;
    }
}
