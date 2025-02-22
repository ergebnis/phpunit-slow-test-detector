<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\MaximumCount
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Count
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidMaximumCount
 */
final class MaximumCountTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromCountRejectsInvalidCount()
    {
        $count = Count::fromInt(0);

        $this->expectException(Exception\InvalidMaximumCount::class);

        MaximumCount::fromCount($count);
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::greaterThanZero
     */
    public function testFromCountReturnsMaximumCount(int $value)
    {
        $count = Count::fromInt($value);

        $maximumCount = MaximumCount::fromCount($count);

        self::assertSame($count, $maximumCount->toCount());
    }

    public function testDefaultReturnsMaximumCount()
    {
        $maximumCount = MaximumCount::default();

        $expected = Count::fromInt(10);

        self::assertEquals($expected, $maximumCount->toCount());
    }
}
