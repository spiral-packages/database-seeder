<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Scaffolder\Declaration;

use Spiral\DatabaseSeeder\Factory\AbstractFactory;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;

class FactoryDeclaration extends ClassDeclaration implements DependedInterface
{
    public function __construct(string $name, string $comment = '')
    {
        parent::__construct($name, 'AbstractFactory', [], $comment);
    }

    public function getDependencies(): array
    {
        return [AbstractFactory::class => null];
    }

    public function declare(): void
    {
        $entity = $this->method('entity')->setPublic()->setReturn('string');
        $declaration = $this->method('definition')->setPublic()->setReturn('array');

        $entity->setComment('Returns a fully qualified database entity class name');
        $declaration->setComment('Returns array with generation rules');

        $this->method('entity')->getSource()->addLine('// return App\Entity\User::class;');
        $this->method('definition')->getSource()->addLine('return [];');
    }
}
