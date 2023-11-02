<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Comparator;

use Ergebnis\PHPUnit\SlowTestDetector\Comparator;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Comparator\DurationComparator::class)]
#[Framework\Attributes\UsesClass(Duration::class)]
final class DurationComparatorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testReturnsMinusOneWhenOneIsLessThanTwo(): void
    {
        $one = Duration::fromSecondsAndNanoseconds(
            5,
            0,
        );

        $two = Duration::fromSecondsAndNanoseconds(
            5,
            1,
        );

        $comparator = new Comparator\DurationComparator();

        self::assertSame(-1, $comparator->compare($one, $two));
    }

    public function testReturnsZeroWhenOneEqualsTwo(): void
    {
        $one = Duration::fromSecondsAndNanoseconds(
            5,
            0,
        );

        $two = Duration::fromSecondsAndNanoseconds(
            5,
            0,
        );

        $comparator = new Comparator\DurationComparator();

        self::assertSame(0, $comparator->compare($one, $two));
    }

    public function testReturnsPlusOneWhenOneIsGreaterThanTwo(): void
    {
        $one = Duration::fromSecondsAndNanoseconds(
            5,
            1,
        );

        $two = Duration::fromSecondsAndNanoseconds(
            5,
            0,
        );

        $comparator = new Comparator\DurationComparator();

        self::assertSame(1, $comparator->compare($one, $two));
    }
}
