<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Scaffolder\Declaration;

use Spiral\DatabaseSeeder\Factory\AbstractFactory;
use Spiral\Scaffolder\Declaration\AbstractDeclaration;

final class FactoryDeclaration extends AbstractDeclaration
{
    public const TYPE = 'factory';

    public function declare(): void
    {

        $this->namespace->addUse(AbstractFactory::class);
        $this->class->setExtends(AbstractFactory::class);

        $this->class
            ->addMethod('entity')
            ->setPublic()
            ->setReturnType('string')
            ->setComment('Returns a fully qualified database entity class name')
            ->addBody('// return App\Entity\User::class;');

        $this->class
            ->addMethod('definition')
            ->setPublic()
            ->setReturnType('array')
            ->setComment('Returns array with generation rules')
            ->addBody('return [];');
    }
}
