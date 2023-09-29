<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Attribute;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
final class ShowQueries
{
}
