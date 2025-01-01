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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Comparator;

use Ergebnis\PHPUnit\SlowTestDetector\Comparator;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 */
final class DurationComparatorTest extends Framework\TestCase
{
    public function testReturnsMinusOneWhenOneIsLessThanTwo()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            5,
            0
        );

        $two = Duration::fromSecondsAndNanoseconds(
            5,
            1
        );

        $comparator = new Comparator\DurationComparator();

        self::assertSame(-1, $comparator->compare($one, $two));
    }

    public function testReturnsZeroWhenOneEqualsTwo()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            5,
            0
        );

        $two = Duration::fromSecondsAndNanoseconds(
            5,
            0
        );

        $comparator = new Comparator\DurationComparator();

        self::assertSame(0, $comparator->compare($one, $two));
    }

    public function testReturnsPlusOneWhenOneIsGreaterThanTwo()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            5,
            1
        );

        $two = Duration::fromSecondsAndNanoseconds(
            5,
            0
        );

        $comparator = new Comparator\DurationComparator();

        self::assertSame(1, $comparator->compare($one, $two));
    }
}
