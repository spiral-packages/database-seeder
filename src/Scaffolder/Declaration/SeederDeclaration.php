<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Scaffolder\Declaration;

use Spiral\DatabaseSeeder\Seeder\AbstractSeeder;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;

class SeederDeclaration extends ClassDeclaration implements DependedInterface
{
    public function __construct(string $name, string $comment = '')
    {
        parent::__construct($name, 'AbstractSeeder', [], $comment);
    }

    public function getDependencies(): array
    {
        return [AbstractSeeder::class => null];
    }

    public function declare(): void
    {
        $run = $this->method('run')->setPublic()->setReturn('\\Generator');

        $run->setComment('Returns iterable database entities');

        $this->method('run')->getSource()->addLine('// yield UserFactory::new()->createOne();');
    }
}
