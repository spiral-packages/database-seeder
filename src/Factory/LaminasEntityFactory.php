<?php

namespace Spiral\DatabaseSeeder\Factory;


/** @internal */
class LaminasEntityFactory extends \Butschster\EntityFaker\LaminasEntityFactory
{
    public function create(string $class): object
    {
        return new $class;
    }
}
