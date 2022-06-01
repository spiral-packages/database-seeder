<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Scaffolder\Declaration;

use Spiral\DatabaseSeeder\Seeder\AbstractSeeder;
use Spiral\Scaffolder\Declaration\AbstractDeclaration;

final class SeederDeclaration extends AbstractDeclaration
{
    public const TYPE = 'seeder';

    public function declare(): void
    {
        $this->namespace->addUse(AbstractSeeder::class);
        $this->class->setExtends(AbstractSeeder::class);

        $this->class
            ->addMethod('run')
            ->setPublic()
            ->setReturnType(\Generator::class)
            ->setComment('Returns iterable database entities')
            ->addBody('// yield UserFactory::new()->createOne();');
    }
}
