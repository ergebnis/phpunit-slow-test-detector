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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Duration
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidMilliseconds
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidNanoseconds
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidSeconds
 */
final class DurationTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::lessThanZero
     */
    public function testFromSecondsAndNanosecondsRejectsSecondsLessThanZero(int $seconds)
    {
        $nanoseconds = self::faker()->numberBetween(0, 999999999);

        $this->expectException(Exception\InvalidSeconds::class);

        Duration::fromSecondsAndNanoseconds(
            $seconds,
            $nanoseconds
        );
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::lessThanZero
     */
    public function testFromSecondsAndNanosecondsRejectsNanosecondsLessThanZero(int $nanoseconds)
    {
        $seconds = self::faker()->numberBetween(0, 123);

        $this->expectException(Exception\InvalidNanoseconds::class);

        Duration::fromSecondsAndNanoseconds(
            $seconds,
            $nanoseconds
        );
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::greaterThanOne
     */
    public function testFromSecondsAndNanosecondsRejectsNanosecondsGreaterThan999999999(int $offset)
    {
        $seconds = self::faker()->numberBetween(0, 123);
        $nanoseconds = 999999999 + $offset;

        $this->expectException(Exception\InvalidNanoseconds::class);

        Duration::fromSecondsAndNanoseconds(
            $seconds,
            $nanoseconds
        );
    }

    public function testFromSecondsAndNanosecondsReturnsDuration()
    {
        $faker = self::faker();

        $seconds = $faker->numberBetween(0, 999);
        $nanoseconds = $faker->numberBetween(0, 999999999);

        $duration = Duration::fromSecondsAndNanoseconds(
            $seconds,
            $nanoseconds
        );

        self::assertSame($seconds, $duration->seconds());
        self::assertSame($nanoseconds, $duration->nanoseconds());
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::lessThanZero
     */
    public function testFromMillisecondsRejectsInvalidValue(int $milliseconds)
    {
        $this->expectException(Exception\InvalidMilliseconds::class);

        Duration::fromMilliseconds($milliseconds);
    }

    /**
     * @dataProvider provideMillisecondsSecondsAndNanoseconds
     */
    public function testFromMillisecondsReturnsDuration(
        int $milliseconds,
        int $seconds,
        int $nanoseconds
    ) {
        $duration = Duration::fromMilliseconds($milliseconds);

        self::assertSame($seconds, $duration->seconds());
        self::assertSame($nanoseconds, $duration->nanoseconds());
    }

    /**
     * @return \Generator<string, array{0: int, 1: int, 2: int}>
     */
    public static function provideMillisecondsSecondsAndNanoseconds(): iterable
    {
        $values = [
            'zero' => [
                0,
                0,
                0,
            ],
            'one' => [
                1,
                0,
                1000000,
            ],
            'nine-hundred-ninety-nine' => [
                999,
                0,
                999000000,
            ],
            'one-thousand' => [
                1000,
                1,
                0,
            ],
            'one-thousand-and-something' => [
                1234,
                1,
                234000000,
            ],
        ];

        foreach ($values as $key => list($milliseconds, $seconds, $nanoseconds)) {
            yield $key => [
                $milliseconds,
                $seconds,
                $nanoseconds,
            ];
        }
    }

    /**
     * @dataProvider provideDurationDurationAndResultOfAddingDurations
     */
    public function testAddReturnsDuration(
        Duration $one,
        Duration $two,
        Duration $three
    ) {
        self::assertEquals($three, $one->add($two));
    }

    /**
     * @return \Generator<string, array{0: Duration, 1: Duration, 2: Duration}>
     */
    public static function provideDurationDurationAndResultOfAddingDurations(): iterable
    {
        $values = [
            'zero' => [
                Duration::fromMilliseconds(0),
                Duration::fromMilliseconds(0),
                Duration::fromMilliseconds(0),
            ],
            'not-zero' => [
                Duration::fromMilliseconds(1),
                Duration::fromMilliseconds(2),
                Duration::fromMilliseconds(3),
            ],
            'more-than-999999999-nanoseconds' => [
                Duration::fromSecondsAndNanoseconds(
                    1,
                    999999999
                ),
                Duration::fromSecondsAndNanoseconds(
                    2,
                    123456789
                ),
                Duration::fromSecondsAndNanoseconds(
                    4,
                    123456788
                ),
            ],
        ];

        foreach ($values as $key => list($one, $two, $three)) {
            yield $key => [
                $one,
                $two,
                $three,
            ];
        }
    }

    public function testEqualsReturnsFalseWhenSecondsAreDifferent()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        $two = Duration::fromSecondsAndNanoseconds(
            124,
            456
        );

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsFalseWhenNanosecondsAreDifferent()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            457
        );

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValuesAreSame()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        self::assertTrue($one->equals($two));
    }

    public function testIsLessThanReturnsFalseWhenSecondsAreGreater()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        $two = Duration::fromSecondsAndNanoseconds(
            122,
            456
        );

        self::assertFalse($one->isLessThan($two));
    }

    public function testIsLessThanReturnsFalseWhenSecondsAreEqualAndNanosecondsAreGreater()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            455
        );

        self::assertFalse($one->isLessThan($two));
    }

    public function testIsLessThanReturnsFalseWhenValuesAreSame()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        self::assertFalse($one->isLessThan($two));
    }

    public function testIsLessThanReturnsTrueWhenSecondsAreLess()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        $two = Duration::fromSecondsAndNanoseconds(
            124,
            456
        );

        self::assertTrue($one->isLessThan($two));
    }

    public function testIsLessThanReturnsTrueWhenSecondsAreEqualAndNanosecondsAreLess()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            457
        );

        self::assertTrue($one->isLessThan($two));
    }

    public function testIsGreaterThanReturnsFalseWhenSecondsAreLess()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        $two = Duration::fromSecondsAndNanoseconds(
            124,
            456
        );

        self::assertFalse($one->isGreaterThan($two));
    }

    public function testIsGreaterThanReturnsFalseWhenSecondsAreEqualAndNanosecondsAreLess()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            457
        );

        self::assertFalse($one->isGreaterThan($two));
    }

    public function testIsGreaterThanReturnsFalseWhenValuesAreSame()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        self::assertFalse($one->isGreaterThan($two));
    }

    public function testIsGreaterThanReturnsTrueWhenSecondsAreGreater()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        $two = Duration::fromSecondsAndNanoseconds(
            122,
            456
        );

        self::assertTrue($one->isGreaterThan($two));
    }

    public function testIsGreaterThanReturnsTrueWhenSecondsAreEqualAndNanosecondsAreGreater()
    {
        $one = Duration::fromSecondsAndNanoseconds(
            123,
            456
        );

        $two = Duration::fromSecondsAndNanoseconds(
            123,
            455
        );

        self::assertTrue($one->isGreaterThan($two));
    }
}
