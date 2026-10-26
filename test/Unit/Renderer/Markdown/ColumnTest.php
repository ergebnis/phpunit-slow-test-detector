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
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\Column
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\Alignment
 */
final class ColumnTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsColumn(): void
    {
        $heading = self::faker()->word();
        $alignment = Renderer\Markdown\Alignment::center();

        $column = Renderer\Markdown\Column::create(
            $heading,
            $alignment,
        );

        self::assertSame($heading, $column->heading());
        self::assertSame($alignment, $column->alignment());
    }
}
