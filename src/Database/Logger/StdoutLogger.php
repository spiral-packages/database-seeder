<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

final class StdoutLogger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @var list<string>
     */
    private array $informationalMarkers = [
        'pgc.oid',
        'information_schema',
        'pg_catalog',
        'pg_type',
        'pg_indexes',
        'pg_constraint',
    ];

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $message = (string)$message;
        // skip informational queries
        foreach ($this->informationalMarkers as $marker) {
            if (\str_contains($message, $marker)) {
                return;
            }
        }

        echo $this->colorMessage($message) . PHP_EOL;
    }

    private function colorMessage(string $sql): string
    {
        // Colors SQL using ANSI colors
        $sql = preg_replace(
            '/\b(SELECT|UPDATE|INSERT|DELETE)\b/i',
            "\033[1;32m$1\033[0m",
            $sql,
        );

        $keyword = '/\b(ADD|ALTER|AND|AS|ASC|BETWEEN|BY|CASE|CHECK|COLUMN|CONSTRAINT|CREATE|CROSS|CURRENT_DATE|CURRENT_TIME|CURRENT_TIMESTAMP|DATABASE|DEFAULT|DELETE|DESC|DISTINCT|DROP|ELSE|END|EXISTS|FOREIGN|FROM|FULL|GROUP|HAVING|IN|INDEX|INNER|INSERT|INTERSECT|INTO|IS|JOIN|LEFT|LIKE|LIMIT|NOT|NULL|ON|OR|ORDER|OUTER|PRIMARY|REFERENCES|RIGHT|SELECT|SET|TABLE|THEN|TO|TRUNCATE|UNION|UNIQUE|UPDATE|VALUES|WHEN|WHERE)\b/i';

        return \preg_replace(
            $keyword,
            "\033[1;31m$1\033[0m",
            $sql,
        );
    }
}
