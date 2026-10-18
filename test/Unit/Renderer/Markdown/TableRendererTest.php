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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Renderer\Markdown;

use Ergebnis\PHPUnit\SlowTestDetector\Renderer;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\TableRenderer
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\Alignment
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\Column
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\Table
 */
final class TableRendererTest extends Framework\TestCase
{
    public function testRenderReturnsTableWithHeadingsDelimiterRowAndRows(): void
    {
        $table = Renderer\Markdown\Table::create(
            Renderer\Markdown\Column::create(
                '#',
                Renderer\Markdown\Alignment::right(),
            ),
            Renderer\Markdown\Column::create(
                'Test',
                Renderer\Markdown\Alignment::left(),
            ),
            Renderer\Markdown\Column::create(
                'Mode',
                Renderer\Markdown\Alignment::center(),
            ),
        )
            ->withRow(
                '1',
                'foo',
                'bar',
            )
            ->withRow(
                '2',
                'baz',
                'qux',
            );

        $tableRenderer = new Renderer\Markdown\TableRenderer();

        $expected = \implode("\n", [
            '| # | Test | Mode |',
            '|--:|:--|:-:|',
            '| 1 | foo | bar |',
            '| 2 | baz | qux |',
            '',
        ]);

        self::assertSame($expected, $tableRenderer->render($table));
    }

    public function testRenderReturnsTableWithoutRowsWhenTableHasNoRows(): void
    {
        $table = Renderer\Markdown\Table::create(
            Renderer\Markdown\Column::create(
                '#',
                Renderer\Markdown\Alignment::right(),
            ),
            Renderer\Markdown\Column::create(
                'Test',
                Renderer\Markdown\Alignment::left(),
            ),
        );

        $tableRenderer = new Renderer\Markdown\TableRenderer();

        $expected = \implode("\n", [
            '| # | Test |',
            '|--:|:--|',
            '',
        ]);

        self::assertSame($expected, $tableRenderer->render($table));
    }

    public function testRenderEscapesPipeInCellsAndHeadings(): void
    {
        $table = Renderer\Markdown\Table::create(
            Renderer\Markdown\Column::create(
                'foo|bar',
                Renderer\Markdown\Alignment::left(),
            ),
            Renderer\Markdown\Column::create(
                'Test',
                Renderer\Markdown\Alignment::left(),
            ),
        )->withRow(
            'baz|qux',
            '`a|b`',
        );

        $tableRenderer = new Renderer\Markdown\TableRenderer();

        $expected = \implode("\n", [
            '| foo\|bar | Test |',
            '|:--|:--|',
            '| baz\|qux | `a\|b` |',
            '',
        ]);

        self::assertSame($expected, $tableRenderer->render($table));
    }

    public function testRenderReplacesLineBreaksInCellsWithSpaces(): void
    {
        $table = Renderer\Markdown\Table::create(
            Renderer\Markdown\Column::create(
                "Line\nBreak",
                Renderer\Markdown\Alignment::left(),
            ),
            Renderer\Markdown\Column::create(
                'Test',
                Renderer\Markdown\Alignment::left(),
            ),
            Renderer\Markdown\Column::create(
                'Mode',
                Renderer\Markdown\Alignment::left(),
            ),
        )->withRow(
            "foo\r\nbar",
            "foo\rbar",
            "foo\nbar",
        );

        $tableRenderer = new Renderer\Markdown\TableRenderer();

        $expected = \implode("\n", [
            '| Line Break | Test | Mode |',
            '|:--|:--|:--|',
            '| foo bar | foo bar | foo bar |',
            '',
        ]);

        self::assertSame($expected, $tableRenderer->render($table));
    }
}
