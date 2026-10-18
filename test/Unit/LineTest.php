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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Line;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Line
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidLine
 */
final class LineTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::lessThanOne
     */
    public function testFromIntThrowsInvalidLineWhenValueIsNotGreaterThanZero(int $value): void
    {
        $this->expectException(Exception\InvalidLine::class);

        Line::fromInt($value);
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::greaterThanZero
     */
    public function testFromIntReturnsLineWhenValueIsGreaterThanZero(int $value): void
    {
        $line = Line::fromInt($value);

        self::assertSame($value, $line->toInt());
    }
}
