<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\DataProvider;
use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Count
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidCount
 */
final class CountTest extends Framework\TestCase
{
    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::lessThanZero
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::zero
     */
    public function testFromIntRejectsInvalidValue(int $value)
    {
        $this->expectException(Exception\InvalidCount::class);

        Count::fromInt($value);
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::greaterThanZero
     */
    public function testFromIntReturnsCount(int $value)
    {
        $count = Count::fromInt($value);

        self::assertSame($value, $count->toInt());
    }
}
