<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Attribute;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
final class ShowQueries
{
}
