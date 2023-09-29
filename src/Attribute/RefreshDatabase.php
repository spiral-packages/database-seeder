<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Attribute;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Spiral\Attributes\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target({"METHOD"})
 * @Annotation\Attributes({
 *     @Annotation\Attribute("database", type="string", required=false),
 *     @Annotation\Attribute("except", type="array", required=false),
 *  })
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
final class RefreshDatabase
{
    /**
     * @param non-empty-string|null $database
     */
    public function __construct(
        public ?string $database = null,
        public array $except = []
    ) {
    }
}
