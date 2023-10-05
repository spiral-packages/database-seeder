<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Attribute;

use Spiral\Attributes\NamedArgumentConstructor;

#[\Attribute(\Attribute::TARGET_METHOD), NamedArgumentConstructor]
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
