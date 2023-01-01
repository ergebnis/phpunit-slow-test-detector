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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidMaximumDuration
 */
final class MaximumDurationTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::lessThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testFromMillisecondsRejectsInvalidValue(int $milliseconds): void
    {
        $this->expectException(Exception\InvalidMaximumDuration::class);

        MaximumDuration::fromMilliseconds($milliseconds);
    }

    /**
     * @dataProvider provideMillisecondsAndTelemetryDuration
     */
    public function testFromMillisecondsReturnsMaximumDuration(
        int $milliseconds,
        Event\Telemetry\Duration $duration,
    ): void {
        $maximumDuration = MaximumDuration::fromMilliseconds($milliseconds);

        self::assertEquals($duration, $maximumDuration->toTelemetryDuration());
    }

    /**
     * @return \Generator<int, array{0: int, Event\Telemetry\Duration}>
     */
    public static function provideMillisecondsAndTelemetryDuration(): \Generator
    {
        $values = [
            1 => Event\Telemetry\Duration::fromSecondsAndNanoseconds(0, 1_000_000),
            999 => Event\Telemetry\Duration::fromSecondsAndNanoseconds(0, 999_000_000),
            1_000 => Event\Telemetry\Duration::fromSecondsAndNanoseconds(1, 0),
            1_234 => Event\Telemetry\Duration::fromSecondsAndNanoseconds(1, 234_000_000),
        ];

        foreach ($values as $milliseconds => $duration) {
            yield $milliseconds => [
                $milliseconds,
                $duration,
            ];
        }
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::lessThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testFromSecondsRejectsInvalidValue(int $seconds): void
    {
        $this->expectException(Exception\InvalidMaximumDuration::class);

        MaximumDuration::fromSeconds($seconds);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     */
    public function testFromSecondsReturnsMaximumDuration(int $seconds): void
    {
        $maximumDuration = MaximumDuration::fromSeconds($seconds);

        $expected = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $seconds,
            0,
        );

        self::assertEquals($expected, $maximumDuration->toTelemetryDuration());
    }
}
