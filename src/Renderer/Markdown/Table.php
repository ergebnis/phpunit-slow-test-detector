<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2026 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown;

/**
 * @internal
 */
final class Table
{
    /**
     * @var list<Column>
     */
    private array $columns;

    /**
     * @var list<list<string>>
     */
    private array $rows;

    /**
     * @param list<Column>       $columns
     * @param list<list<string>> $rows
     */
    private function __construct(
        array $columns,
        array $rows
    ) {
        $this->columns = $columns;
        $this->rows = $rows;
    }

    public static function create(Column ...$columns): self
    {
        return new self(
            \array_values($columns),
            [],
        );
    }

    public function withRow(string ...$cells): self
    {
        $rows = $this->rows;

        $rows[] = \array_values($cells);

        return new self(
            $this->columns,
            $rows,
        );
    }

    /**
     * @return list<Column>
     */
    public function columns(): array
    {
        return $this->columns;
    }

    /**
     * @return list<list<string>>
     */
    public function rows(): array
    {
        return $this->rows;
    }
}
