<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Comparator;

use Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator;
use Ergebnis\Test\Util;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator
 */
final class DurationComparatorTest extends Framework\TestCase
{
    use Util\Helper;

    public function testReturnsMinusOneWhenOneIsLessThanTwo(): void
    {
        $one = Event\Telemetry\Duration::fromSeconds(5);
        $two = Event\Telemetry\Duration::fromSeconds(6);

        $comparator = new DurationComparator();

        self::assertSame(-1, $comparator->compare($one, $two));
    }

    public function testReturnsZeroWhenOneEqualsTwo(): void
    {
        $one = Event\Telemetry\Duration::fromSeconds(5);
        $two = Event\Telemetry\Duration::fromSeconds(5);

        $comparator = new DurationComparator();

        self::assertSame(0, $comparator->compare($one, $two));
    }

    public function testReturnsPlusOneWhenOneIsGreaterThanTwo(): void
    {
        $one = Event\Telemetry\Duration::fromSeconds(5);
        $two = Event\Telemetry\Duration::fromSeconds(4);

        $comparator = new DurationComparator();

        self::assertSame(1, $comparator->compare($one, $two));
    }
}
