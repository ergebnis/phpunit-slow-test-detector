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

use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console\Unit
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 */
final class UnitTest extends Framework\TestCase
{
    public function testHoursReturnsUnit()
    {
        $unit = Reporter\Console\Unit::hours();

        self::assertSame('hours', $unit->toString());
    }

    public function testMinutesReturnsUnit()
    {
        $unit = Reporter\Console\Unit::minutes();

        self::assertSame('minutes', $unit->toString());
    }

    public function testSecondsReturnsUnit()
    {
        $unit = Reporter\Console\Unit::seconds();

        self::assertSame('seconds', $unit->toString());
    }

    public function testEqualsReturnsFalseWhenUnitsAreNotEqual()
    {
        $one = Reporter\Console\Unit::hours();
        $two = Reporter\Console\Unit::minutes();

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenUnitsAreEqual()
    {
        $one = Reporter\Console\Unit::hours();
        $two = Reporter\Console\Unit::hours();

        self::assertTrue($one->equals($two));
        self::assertTrue(Reporter\Console\Unit::minutes()->equals(Reporter\Console\Unit::minutes()));
        self::assertTrue(Reporter\Console\Unit::hours()->equals(Reporter\Console\Unit::hours()));
    }

    public function testIsGreaterThanReturnsFalseWhenUnitIsNotGreater()
    {
        $one = Reporter\Console\Unit::minutes();
        $two = Reporter\Console\Unit::hours();

        self::assertFalse($one->isGreaterThan($two));
    }

    public function testIsGreaterThanReturnsTrueWhenUnitIsGreater()
    {
        $one = Reporter\Console\Unit::hours();
        $two = Reporter\Console\Unit::minutes();

        self::assertTrue($one->isGreaterThan($two));
    }

    /**
     * @dataProvider provideExpectedUnitAndDuration
     */
    public function testFromDurationReturnsExpectedUnit(
        Reporter\Console\Unit $expectedUnit,
        Duration $duration
    ) {
        $unit = Reporter\Console\Unit::fromDuration($duration);

        self::assertTrue($expectedUnit->equals($unit));
    }

    /**
     * @return \Generator<string, array{0: \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console\Unit, 1: Duration}>
     */
    public static function provideExpectedUnitAndDuration(): iterable
    {
        $values = [
            'zero' => [
                Reporter\Console\Unit::seconds(),
                Duration::fromMilliseconds(0),
            ],
            'milliseconds' => [
                Reporter\Console\Unit::seconds(),
                Duration::fromMilliseconds(500),
            ],
            'seconds' => [
                Reporter\Console\Unit::seconds(),
                Duration::fromMilliseconds(59999),
            ],
            'minutes' => [
                Reporter\Console\Unit::minutes(),
                Duration::fromMilliseconds(60000),
            ],
            'minutes-large' => [
                Reporter\Console\Unit::minutes(),
                Duration::fromMilliseconds(3599999),
            ],
            'hours' => [
                Reporter\Console\Unit::hours(),
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
        Reporter\Console\Unit $expectedUnit,
        array $durations
    ) {
        $unit = Reporter\Console\Unit::fromDurations(...$durations);

        self::assertTrue($expectedUnit->equals($unit));
    }

    /**
     * @return \Generator<string, array{0: \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console\Unit, 1: list<Duration>}>
     */
    public static function provideExpectedUnitAndDurations(): iterable
    {
        $values = [
            'all-seconds' => [
                Reporter\Console\Unit::seconds(),
                [
                    Duration::fromMilliseconds(100),
                    Duration::fromMilliseconds(200),
                ],
            ],
            'mixed-seconds-and-minutes' => [
                Reporter\Console\Unit::minutes(),
                [
                    Duration::fromMilliseconds(100),
                    Duration::fromMilliseconds(60000),
                ],
            ],
            'mixed-all' => [
                Reporter\Console\Unit::hours(),
                [
                    Duration::fromMilliseconds(100),
                    Duration::fromMilliseconds(60000),
                    Duration::fromMilliseconds(3600000),
                ],
            ],
            'empty' => [
                Reporter\Console\Unit::seconds(),
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
