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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Reporter\Formatter;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Formatter\Unit
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 */
final class UnitTest extends Framework\TestCase
{
    public function testHoursReturnsUnit()
    {
        $unit = Reporter\Formatter\Unit::hours();

        self::assertSame('hours', $unit->toString());
    }

    public function testMinutesReturnsUnit()
    {
        $unit = Reporter\Formatter\Unit::minutes();

        self::assertSame('minutes', $unit->toString());
    }

    public function testSecondsReturnsUnit()
    {
        $unit = Reporter\Formatter\Unit::seconds();

        self::assertSame('seconds', $unit->toString());
    }

    public function testEqualsReturnsFalseWhenUnitsAreNotEqual()
    {
        $one = Reporter\Formatter\Unit::hours();
        $two = Reporter\Formatter\Unit::minutes();

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenUnitsAreEqual()
    {
        $one = Reporter\Formatter\Unit::hours();
        $two = Reporter\Formatter\Unit::hours();

        self::assertTrue($one->equals($two));
        self::assertTrue(Reporter\Formatter\Unit::minutes()->equals(Reporter\Formatter\Unit::minutes()));
        self::assertTrue(Reporter\Formatter\Unit::hours()->equals(Reporter\Formatter\Unit::hours()));
    }

    public function testIsGreaterThanReturnsFalseWhenUnitIsNotGreater()
    {
        $one = Reporter\Formatter\Unit::minutes();
        $two = Reporter\Formatter\Unit::hours();

        self::assertFalse($one->isGreaterThan($two));
    }

    public function testIsGreaterThanReturnsTrueWhenUnitIsGreater()
    {
        $one = Reporter\Formatter\Unit::hours();
        $two = Reporter\Formatter\Unit::minutes();

        self::assertTrue($one->isGreaterThan($two));
    }

    /**
     * @dataProvider provideExpectedUnitAndDuration
     */
    public function testFromDurationReturnsExpectedUnit(
        Reporter\Formatter\Unit $expectedUnit,
        Duration $duration
    ) {
        $unit = Reporter\Formatter\Unit::fromDuration($duration);

        self::assertTrue($expectedUnit->equals($unit));
    }

    /**
     * @return \Generator<string, array{0: Reporter\Formatter\Unit, 1: Duration}>
     */
    public static function provideExpectedUnitAndDuration(): iterable
    {
        $values = [
            'zero' => [
                Reporter\Formatter\Unit::seconds(),
                Duration::fromMilliseconds(0),
            ],
            'milliseconds' => [
                Reporter\Formatter\Unit::seconds(),
                Duration::fromMilliseconds(500),
            ],
            'seconds' => [
                Reporter\Formatter\Unit::seconds(),
                Duration::fromMilliseconds(59999),
            ],
            'minutes' => [
                Reporter\Formatter\Unit::minutes(),
                Duration::fromMilliseconds(60000),
            ],
            'minutes-large' => [
                Reporter\Formatter\Unit::minutes(),
                Duration::fromMilliseconds(3599999),
            ],
            'hours' => [
                Reporter\Formatter\Unit::hours(),
                Duration::fromMilliseconds(3600000),
            ],
        ];

        foreach ($values as $key => list($expectedUnit, $duration)) {
            yield $key => [
                $expectedUnit,
                $duration,
            ];
        }
    }

    /**
     * @dataProvider provideExpectedUnitAndDurations
     *
     * @param list<Duration> $durations
     */
    public function testFromDurationsReturnsLargestUnit(
        Reporter\Formatter\Unit $expectedUnit,
        array $durations
    ) {
        $unit = Reporter\Formatter\Unit::fromDurations(...$durations);

        self::assertTrue($expectedUnit->equals($unit));
    }

    /**
     * @return \Generator<string, array{0: Reporter\Formatter\Unit, 1: list<Duration>}>
     */
    public static function provideExpectedUnitAndDurations(): iterable
    {
        $values = [
            'all-seconds' => [
                Reporter\Formatter\Unit::seconds(),
                [
                    Duration::fromMilliseconds(100),
                    Duration::fromMilliseconds(200),
                ],
            ],
            'mixed-seconds-and-minutes' => [
                Reporter\Formatter\Unit::minutes(),
                [
                    Duration::fromMilliseconds(100),
                    Duration::fromMilliseconds(60000),
                ],
            ],
            'mixed-all' => [
                Reporter\Formatter\Unit::hours(),
                [
                    Duration::fromMilliseconds(100),
                    Duration::fromMilliseconds(60000),
                    Duration::fromMilliseconds(3600000),
                ],
            ],
            'empty' => [
                Reporter\Formatter\Unit::seconds(),
                [],
            ],
        ];

        foreach ($values as $key => list($expectedUnit, $durations)) {
            yield $key => [
                $expectedUnit,
                $durations,
            ];
        }
    }
}
