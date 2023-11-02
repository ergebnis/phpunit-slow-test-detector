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
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\Time;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Time::class)]
#[Framework\Attributes\UsesClass(Duration::class)]
final class TimeTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromSecondsAndNanosecondsRejectsNegativeSeconds(): void
    {
        $this->expectException(Exception\InvalidSeconds::class);

        Time::fromSecondsAndNanoseconds(
            -1,
            0,
        );
    }

    public function testFromSecondsAndNanosecondsRejectsNegativeNanoseconds(): void
    {
        $this->expectException(Exception\InvalidNanoseconds::class);

        Time::fromSecondsAndNanoseconds(
            0,
            -1,
        );
    }

    public function testFromSecondsAndNanosecondsRejectsNanosecondsGreaterThan999999999(): void
    {
        $this->expectException(Exception\InvalidNanoseconds::class);

        Time::fromSecondsAndNanoseconds(
            0,
            1000000000,
        );
    }

    public function testFromSecondsAndNanosecondsReturnsTime(): void
    {
        $faker = self::faker();

        $seconds = $faker->numberBetween(0, 999);
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
            $startSeconds,
            $startNanoseconds,
        );

        $end = Time::fromSecondsAndNanoseconds(
            $endSeconds,
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
        int $startSeconds,
        int $startNanoseconds,
        int $endSeconds,
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
     * @return \Generator<string, array{0: int, 1: int, 2: int, 3: int, 4: Duration}>
     */
    public static function provideStartEndAndDuration(): \Generator
    {
        $values = [
            'start-equal-to-end' => [
                10,
                50,
                10,
                50,
                Duration::fromSecondsAndNanoseconds(
                    0,
                    0,
                ),
            ],
            'start-smaller-than-end' => [
                10,
                50,
                12,
                70,
                Duration::fromSecondsAndNanoseconds(
                    2,
                    20,
                ),
            ],
            'start-nanoseconds-greater-than-end-nanoseconds' => [
                10,
                50,
                12,
                30,
                Duration::fromSecondsAndNanoseconds(
                    1,
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
