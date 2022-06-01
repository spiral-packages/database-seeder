<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Console\Command;

use Psr\Container\ContainerInterface;
use Spiral\Core\FactoryInterface;
use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Spiral\Files\FilesInterface;
use Spiral\Reactor\Writer;
use Spiral\Scaffolder\Command\AbstractCommand;
use Spiral\Scaffolder\Config\ScaffolderConfig;
use Spiral\Scaffolder\Declaration\DeclarationInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractScaffolderCommand extends AbstractCommand
{
    public function __construct(
        ScaffolderConfig $config,
        FilesInterface $files,
        ContainerInterface $container,
        FactoryInterface $factory,
        private DatabaseSeederConfig $seederConfig
    ) {
        parent::__construct($config, $files, $container, $factory);
    }

    protected function writeDeclaration(DeclarationInterface $declaration): void
    {
        $filename = $this->getFilename($declaration::TYPE, (string)$this->argument('name'));
        $filename = $this->files->normalizePath($filename);

        if ($this->files->exists($filename)) {
            /** @psalm-suppress PossiblyNullArgument */
            $io = new SymfonyStyle($this->input, $this->output);

            $io->error(
                "Unable to create {$declaration->getClass()->getName()} declaration, file {$filename} already exists."
            );

            return;
        }

        (new Writer($this->files))->write($filename, $declaration->getFile());
    }

    private function getFilename(string $type, string $class): string
    {
        $dir = match ($type) {
            'factory' => $this->seederConfig->getFactoriesDirectory(),
            default => $this->seederConfig->getSeedersDirectory()
        };

        return $dir . FilesInterface::SEPARATOR . $this->config->className($type, $class) . '.php';
    }
}
