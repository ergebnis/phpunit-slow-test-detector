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

use Ergebnis\DataProvider;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Duration::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidMilliseconds::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidNanoseconds::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidSeconds::class)]
final class DurationTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'lessThanZero')]
    public function testFromSecondsAndNanosecondsRejectsSecondsLessThanZero(int $seconds): void
    {
        $nanoseconds = self::faker()->numberBetween(0, 999_999_999);

        $this->expectException(Exception\InvalidSeconds::class);

        Duration::fromSecondsAndNanoseconds(
            $seconds,
            $nanoseconds,
        );
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'lessThanZero')]
    public function testFromSecondsAndNanosecondsRejectsNanosecondsLessThanZero(int $nanoseconds): void
    {
        $seconds = self::faker()->numberBetween(0, 123);

        $this->expectException(Exception\InvalidNanoseconds::class);

        Duration::fromSecondsAndNanoseconds(
            $seconds,
            $nanoseconds,
        );
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanOne')]
    public function testFromSecondsAndNanosecondsRejectsNanosecondsGreaterThan999999999(int $offset): void
    {
        $seconds = self::faker()->numberBetween(0, 123);
        $nanoseconds = 999_999_999 + $offset;

        $this->expectException(Exception\InvalidNanoseconds::class);

        Duration::fromSecondsAndNanoseconds(
            $seconds,
            $nanoseconds,
        );
    }

    public function testFromSecondsAndNanosecondsReturnsDuration(): void
    {
        $faker = self::faker();

        $seconds = $faker->numberBetween(0, 999);
        $nanoseconds = $faker->numberBetween(0, 999_999_999);

        $duration = Duration::fromSecondsAndNanoseconds(
            $seconds,
            $nanoseconds,
        );

        self::assertSame($seconds, $duration->seconds());
        self::assertSame($nanoseconds, $duration->nanoseconds());
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'lessThanZero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    public function testFromMillisecondsRejectsInvalidValue(int $milliseconds): void
    {
        $this->expectException(Exception\InvalidMilliseconds::class);

        Duration::fromMilliseconds($milliseconds);
    }

    #[Framework\Attributes\DataProvider('provideMillisecondsSecondsAndNanoseconds')]
    public function testFromMillisecondsReturnsDuration(
        int $milliseconds,
        int $seconds,
        int $nanoseconds,
    ): void {
        $duration = Duration::fromMilliseconds($milliseconds);

        self::assertSame($seconds, $duration->seconds());
        self::assertSame($nanoseconds, $duration->nanoseconds());
    }

    /**
     * @return \Generator<int, array{0: int, 1: int, 2: int}>
     */
    public static function provideMillisecondsSecondsAndNanoseconds(): \Generator
    {
        $values = [
            1 => [
                0,
                1_000_000,
            ],
            999 => [
                0,
                999_000_000,
            ],
            1_000 => [
                1,
                0,
            ],
            1_234 => [
                1,
                234_000_000,
            ],
        ];

        foreach ($values as $milliseconds => [$seconds, $nanoseconds]) {
            yield $milliseconds => [
                $milliseconds,
                $seconds,
                $nanoseconds,
            ];
        }
    }

    public function testIsLessThanReturnsFalseWhenSecondsAreGreater(): void
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456,
        );

        $two = Duration::fromSecondsAndNanoseconds(
            122,
            456,
        );

        self::assertFalse($one->isLessThan($two));
    }

    public function testIsLessThanReturnsFalseWhenSecondsAreEqualAndNanosecondsAreGreater(): void
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456,
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            455,
        );

        self::assertFalse($one->isLessThan($two));
    }

    public function testIsLessThanReturnsFalseWhenValuesAreSame(): void
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456,
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            456,
        );

        self::assertFalse($one->isLessThan($two));
    }

    public function testIsLessThanReturnsTrueWhenSecondsAreLess(): void
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456,
        );

        $two = Duration::fromSecondsAndNanoseconds(
            124,
            456,
        );

        self::assertTrue($one->isLessThan($two));
    }

    public function testIsLessThanReturnsTrueWhenSecondsAreEqualAndNanosecondsAreLess(): void
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456,
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            457,
        );

        self::assertTrue($one->isLessThan($two));
    }

    public function testIsGreaterThanReturnsFalseWhenSecondsAreLess(): void
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456,
        );

        $two = Duration::fromSecondsAndNanoseconds(
            124,
            456,
        );

        self::assertFalse($one->isGreaterThan($two));
    }

    public function testIsGreaterThanReturnsFalseWhenSecondsAreEqualAndNanosecondsAreLess(): void
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456,
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            457,
        );

        self::assertFalse($one->isGreaterThan($two));
    }

    public function testIsGreaterThanReturnsFalseWhenValuesAreSame(): void
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456,
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            456,
        );

        self::assertFalse($one->isGreaterThan($two));
    }

    public function testIsGreaterThanReturnsTrueWhenSecondsAreGreater(): void
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456,
        );

        $two = Duration::fromSecondsAndNanoseconds(
            122,
            456,
        );

        self::assertTrue($one->isGreaterThan($two));
    }

    public function testIsGreaterThanReturnsTrueWhenSecondsAreEqualAndNanosecondsAreGreater(): void
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456,
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            455,
        );

        self::assertTrue($one->isGreaterThan($two));
    }
}
