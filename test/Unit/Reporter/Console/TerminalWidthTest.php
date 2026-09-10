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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Reporter\Console;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\Width;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console\TerminalWidth
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidTerminalWidth
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Width
 */
final class TerminalWidthTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::zero
     */
    public function testFromWidthThrowsInvalidTerminalWidthWhenWidthIsNotGreaterThanZero(int $value)
    {
        $width = Width::fromInt($value);

        $this->expectException(Exception\InvalidTerminalWidth::class);

        Reporter\Console\TerminalWidth::fromWidth($width);
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::greaterThanZero
     */
    public function testFromWidthReturnsTerminalWidthWhenWidthIsGreaterThanZero(int $value)
    {
        $width = Width::fromInt($value);

        $terminalWidth = Reporter\Console\TerminalWidth::fromWidth($width);

        self::assertSame($width, $terminalWidth->toWidth());
    }

    public function testDefaultReturnsTerminalWidth()
    {
        $terminalWidth = Reporter\Console\TerminalWidth::default();

        self::assertSame(80, $terminalWidth->toWidth()->toInt());
    }
}
