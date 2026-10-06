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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Reporter;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Unit
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 */
final class UnitTest extends Framework\TestCase
{
    public function testHoursReturnsUnit(): void
    {
        $unit = Reporter\Unit::hours();

        self::assertSame('hours', $unit->toString());
    }

    public function testMinutesReturnsUnit(): void
    {
        $unit = Reporter\Unit::minutes();

        self::assertSame('minutes', $unit->toString());
    }

    public function testSecondsReturnsUnit(): void
    {
        $unit = Reporter\Unit::seconds();

        self::assertSame('seconds', $unit->toString());
    }

    public function testEqualsReturnsFalseWhenUnitsAreNotEqual(): void
    {
        $one = Reporter\Unit::hours();
        $two = Reporter\Unit::minutes();

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenUnitsAreEqual(): void
    {
        $one = Reporter\Unit::hours();
        $two = Reporter\Unit::hours();

        self::assertTrue($one->equals($two));
        self::assertTrue(Reporter\Unit::minutes()->equals(Reporter\Unit::minutes()));
        self::assertTrue(Reporter\Unit::hours()->equals(Reporter\Unit::hours()));
    }

    public function testIsGreaterThanReturnsFalseWhenUnitIsNotGreater(): void
    {
        $one = Reporter\Unit::minutes();
        $two = Reporter\Unit::hours();

        self::assertFalse($one->isGreaterThan($two));
    }

    public function testIsGreaterThanReturnsTrueWhenUnitIsGreater(): void
    {
        $one = Reporter\Unit::hours();
        $two = Reporter\Unit::minutes();

        self::assertTrue($one->isGreaterThan($two));
    }

    /**
     * @dataProvider provideExpectedUnitAndDuration
     */
    public function testFromDurationReturnsExpectedUnit(
        Reporter\Unit $expectedUnit,
        Duration $duration
    ): void {
        $unit = Reporter\Unit::fromDuration($duration);

        self::assertTrue($expectedUnit->equals($unit));
    }

    /**
     * @return \Generator<string, array{0: Reporter\Unit, 1: Duration}>
     */
    public static function provideExpectedUnitAndDuration(): iterable
    {
        $values = [
            'zero' => [
                Reporter\Unit::seconds(),
                Duration::fromMilliseconds(0),
            ],
            'milliseconds' => [
                Reporter\Unit::seconds(),
                Duration::fromMilliseconds(500),
            ],
            'seconds' => [
                Reporter\Unit::seconds(),
                Duration::fromMilliseconds(59999),
            ],
            'minutes' => [
                Reporter\Unit::minutes(),
                Duration::fromMilliseconds(60000),
            ],
            'minutes-large' => [
                Reporter\Unit::minutes(),
                Duration::fromMilliseconds(3599999),
            ],
            'hours' => [
                Reporter\Unit::hours(),
                Duration::fromMilliseconds(3600000),
            ],
        ];

        foreach ($values as $key => [$expectedUnit, $duration]) {
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
        Reporter\Unit $expectedUnit,
        array $durations
    ): void {
        $unit = Reporter\Unit::fromDurations(...$durations);

        self::assertTrue($expectedUnit->equals($unit));
    }

    /**
     * @return \Generator<string, array{0: Reporter\Unit, 1: list<Duration>}>
     */
    public static function provideExpectedUnitAndDurations(): iterable
    {
        $values = [
            'all-seconds' => [
                Reporter\Unit::seconds(),
                [
                    Duration::fromMilliseconds(100),
                    Duration::fromMilliseconds(200),
                ],
            ],
            'mixed-seconds-and-minutes' => [
                Reporter\Unit::minutes(),
                [
                    Duration::fromMilliseconds(100),
                    Duration::fromMilliseconds(60000),
                ],
            ],
            'mixed-all' => [
                Reporter\Unit::hours(),
                [
                    Duration::fromMilliseconds(100),
                    Duration::fromMilliseconds(60000),
                    Duration::fromMilliseconds(3600000),
                ],
            ],
            'empty' => [
                Reporter\Unit::seconds(),
                [],
            ],
        ];

        foreach ($values as $key => [$expectedUnit, $durations]) {
            yield $key => [
                $expectedUnit,
                $durations,
            ];
        }
    }
}
