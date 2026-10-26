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
final class TableRenderer
{
    /**
     * @see https://github.github.com/gfm/#tables-extension-
     */
    public function render(Table $table): string
    {
        $columns = $table->columns();

        $lines = [
            self::row(\array_map(static function (Column $column): string {
                return $column->heading();
            }, $columns)),
            \sprintf(
                '|%s|',
                \implode('|', \array_map(static function (Column $column): string {
                    return $column->alignment()->delimiter();
                }, $columns)),
            ),
        ];

        foreach ($table->rows() as $cells) {
            $lines[] = self::row($cells);
        }

        return \implode(
            "\n",
            $lines,
        ) . "\n";
    }

    /**
     * @param list<string> $cells
     */
    private static function row(array $cells): string
    {
        return \sprintf(
            '| %s |',
            \implode(
                ' | ',
                \array_map(static function (string $cell): string {
                    return self::escape($cell);
                }, $cells),
            ),
        );
    }

    private static function escape(string $cell): string
    {
        return \str_replace(
            [
                "\r\n",
                "\r",
                "\n",
                '|',
            ],
            [
                ' ',
                ' ',
                ' ',
                '\|',
            ],
            $cell,
        );
    }
}
