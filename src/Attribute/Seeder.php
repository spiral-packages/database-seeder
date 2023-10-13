<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Attribute;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Spiral\Attributes\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target({"CLASS"})
 * @Annotation\Attributes({
 *     @Annotation\Attribute("priority", type="integer"),
 * })
 */
#[\Attribute(\Attribute::TARGET_CLASS), NamedArgumentConstructor]
class Seeder
{
    /**
     * @param positive-int $priority Execution priority
     */
    public function __construct(
        public int $priority = 1
    ) {
    }
}
