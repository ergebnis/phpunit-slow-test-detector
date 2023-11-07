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

use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Seconds;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\Time;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Time::class)]
#[Framework\Attributes\UsesClass(Duration::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidNanoseconds::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidSeconds::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidStart::class)]
#[Framework\Attributes\UsesClass(Seconds::class)]
final class TimeTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromSecondsAndNanosecondsRejectsNegativeNanoseconds(): void
    {
        $seconds = Seconds::fromInt(0);

        $this->expectException(Exception\InvalidNanoseconds::class);

        Time::fromSecondsAndNanoseconds(
            $seconds,
            -1,
        );
    }

    public function testFromSecondsAndNanosecondsRejectsNanosecondsGreaterThan999999999(): void
    {
        $seconds = Seconds::fromInt(0);

        $this->expectException(Exception\InvalidNanoseconds::class);

        Time::fromSecondsAndNanoseconds(
            $seconds,
            1000000000,
        );
    }

    public function testFromSecondsAndNanosecondsReturnsTime(): void
    {
        $faker = self::faker();

        $seconds = Seconds::fromInt($faker->numberBetween(0, 999));
        $nanoseconds = $faker->numberBetween(0, 999_999_999);

        $time = Time::fromSecondsAndNanoseconds(
            $seconds,
            $nanoseconds,
        );

        self::assertSame($seconds, $time->seconds());
        self::assertSame($nanoseconds, $time->nanoseconds());
    }

    #[Framework\Attributes\DataProvider('provideStartGreaterThanEnd')]
    public function testDurationRejectsStartGreaterThanEnd(
        int $startSeconds,
        int $startNanoseconds,
        int $endSeconds,
        int $endNanoseconds,
    ): void {
        $start = Time::fromSecondsAndNanoseconds(
            Seconds::fromInt($startSeconds),
            $startNanoseconds,
        );

        $end = Time::fromSecondsAndNanoseconds(
            Seconds::fromInt($endSeconds),
            $endNanoseconds,
        );

        $this->expectException(Exception\InvalidStart::class);

        $end->duration($start);
    }

    /**
     * @return \Generator<string, array{0: int, 1: int, 2: int, 3: int}>
     */
    public static function provideStartGreaterThanEnd(): \Generator
    {
        $values = [
            'seconds-greater' => [
                11,
                1,
                10,
                1,
            ],
            'seconds-and-nanoseconds-greater' => [
                11,
                1,
                10,
                0,
            ],
            'nanoseconds-greater' => [
                10,
                1,
                10,
                0,
            ],
        ];

        foreach ($values as $key => [$startSeconds, $startNanoseconds, $endSeconds, $endNanoseconds]) {
            yield $key => [
                $startSeconds,
                $startNanoseconds,
                $endSeconds,
                $endNanoseconds,
            ];
        }
    }

    #[Framework\Attributes\DataProvider('provideStartEndAndDuration')]
    public function testDurationReturnsDifferenceBetweenEndAndStart(
        Seconds $startSeconds,
        int $startNanoseconds,
        Seconds $endSeconds,
        int $endNanoseconds,
        Duration $duration,
    ): void {
        $start = Time::fromSecondsAndNanoseconds(
            $startSeconds,
            $startNanoseconds,
        );

        $end = Time::fromSecondsAndNanoseconds(
            $endSeconds,
            $endNanoseconds,
        );

        self::assertEquals($duration, $end->duration($start));
    }

    /**
     * @return \Generator<string, array{0: Seconds, 1: int, 2: Seconds, 3: int, 4: Duration}>
     */
    public static function provideStartEndAndDuration(): \Generator
    {
        $values = [
            'start-equal-to-end' => [
                Seconds::fromInt(10),
                50,
                Seconds::fromInt(10),
                50,
                Duration::fromSecondsAndNanoseconds(
                    Seconds::fromInt(0),
                    0,
                ),
            ],
            'start-smaller-than-end' => [
                Seconds::fromInt(10),
                50,
                Seconds::fromInt(12),
                70,
                Duration::fromSecondsAndNanoseconds(
                    Seconds::fromInt(2),
                    20,
                ),
            ],
            'start-nanoseconds-greater-than-end-nanoseconds' => [
                Seconds::fromInt(10),
                50,
                Seconds::fromInt(12),
                30,
                Duration::fromSecondsAndNanoseconds(
                    Seconds::fromInt(1),
                    999999980,
                ),
            ],
        ];

        foreach ($values as $key => [$startSeconds, $startNanoseconds, $endSeconds, $endNanoseconds, $duration]) {
            yield $key => [
                $startSeconds,
                $startNanoseconds,
                $endSeconds,
                $endNanoseconds,
                $duration,
            ];
        }
    }
}
