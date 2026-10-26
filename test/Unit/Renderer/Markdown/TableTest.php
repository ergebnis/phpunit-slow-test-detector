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
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\Table
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\Alignment
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\Column
 */
final class TableTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsTableWithoutRows(): void
    {
        $faker = self::faker();

        $columnOne = Renderer\Markdown\Column::create(
            $faker->word(),
            Renderer\Markdown\Alignment::right(),
        );

        $columnTwo = Renderer\Markdown\Column::create(
            $faker->word(),
            Renderer\Markdown\Alignment::left(),
        );

        $table = Renderer\Markdown\Table::create(
            $columnOne,
            $columnTwo,
        );

        $expected = [
            $columnOne,
            $columnTwo,
        ];

        self::assertSame($expected, $table->columns());
        self::assertSame([], $table->rows());
    }

    public function testWithRowReturnsTableWithRow(): void
    {
        $faker = self::faker();

        $table = Renderer\Markdown\Table::create(
            Renderer\Markdown\Column::create(
                $faker->word(),
                Renderer\Markdown\Alignment::right(),
            ),
            Renderer\Markdown\Column::create(
                $faker->word(),
                Renderer\Markdown\Alignment::left(),
            ),
        );

        $cellOne = $faker->word();
        $cellTwo = $faker->sentence();

        $mutated = $table->withRow(
            $cellOne,
            $cellTwo,
        );

        self::assertNotSame($table, $mutated);
        self::assertSame([], $table->rows());

        $expected = [
            [
                $cellOne,
                $cellTwo,
            ],
        ];

        self::assertSame($expected, $mutated->rows());
    }
}
