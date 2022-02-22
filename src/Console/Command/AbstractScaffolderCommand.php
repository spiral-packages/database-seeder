<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Console\Command;

use Doctrine\Inflector\Rules\English\InflectorFactory;
use Psr\Container\ContainerInterface;
use Spiral\Core\FactoryInterface;
use Spiral\DatabaseSeeder\Config\DatabaseSeederConfig;
use Spiral\Files\FilesInterface;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\FileDeclaration;
use Spiral\Scaffolder\Command\AbstractCommand;
use Spiral\Scaffolder\Config\ScaffolderConfig;
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

    protected function writeDeclaration(ClassDeclaration $declaration, string $type = null): void
    {
        $type = $type ?? static::ELEMENT;

        $filename = $this->getFilename($type, (string)$this->argument('name'));
        $filename = $this->files->normalizePath($filename);

        /** @psalm-suppress PossiblyNullArgument */
        $io = new SymfonyStyle($this->input, $this->output);

        if ($this->files->exists($filename)) {
            $io->error("Unable to create {$declaration->getName()} declaration, file {$filename} already exists.");

            return;
        }

        //File declaration
        $file = new FileDeclaration($this->getClassNamespace($type, (string)$this->argument('name')));

        $file->setDirectives('strict_types=1');
        $file->setComment($this->config->headerLines());
        $file->addElement($declaration);

        $this->files->write(
            $filename,
            $file->render(),
            FilesInterface::READONLY,
            true
        );

        $io->success("Declaration of {$declaration->getName()} has been successfully written into {$filename}.");
    }

    private function getFilename(string $type, string $class): string
    {
        $dir = match ($type) {
            'factory' => $this->seederConfig->getFactoriesDirectory(),
            default => $this->seederConfig->getSeedersDirectory()
        };

        return $dir . FilesInterface::SEPARATOR . $this->config->className($type, $class) . '.php';
    }

    private function getClassNamespace(string $type, string $class): string
    {
        $namespace = match ($type) {
            'factory' => $this->seederConfig->getFactoriesNamespace(),
            default => $this->seederConfig->getSeedersNamespace()
        };

        return  $namespace . '\\' . (new InflectorFactory())->build()->classify($class);
    }
}
