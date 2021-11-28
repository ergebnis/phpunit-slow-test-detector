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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Collector;

use Ergebnis\PHPUnit\SlowTestDetector\Collector\DefaultCollector;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\Test\Example;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Collector\DefaultCollector
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 */
final class DefaultCollectorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testDefaults(): void
    {
        $collector = new DefaultCollector();

        self::assertSame([], $collector->collected());
    }

    public function testCollectCollectsSlowTests(): void
    {
        $faker = self::faker();

        $first = SlowTest::fromTestDurationAndMaximumDuration(
            new Event\Code\Test(
                Example\SleeperTest::class,
                'foo',
                'foo with data set #123',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $second = SlowTest::fromTestDurationAndMaximumDuration(
            new Event\Code\Test(
                Example\SleeperTest::class,
                'bar',
                'bar',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $third = SlowTest::fromTestDurationAndMaximumDuration(
            new Event\Code\Test(
                Example\SleeperTest::class,
                'baz',
                'baz with data set "string"',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $collector = new DefaultCollector();

        $collector->collect($first);
        $collector->collect($second);
        $collector->collect($third);

        $expected = [
            $first,
            $second,
            $third,
        ];

        self::assertSame($expected, $collector->collected());
    }

    public function testCollectDoesNotReplaceSlowTestWhenDurationIsLessThanPreviousSlowTest(): void
    {
        $faker = self::faker();

        $first = SlowTest::fromTestDurationAndMaximumDuration(
            new Event\Code\Test(
                Example\SleeperTest::class,
                'foo',
                'foo with data set #123',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(1),
                $faker->numberBetween(0, 999_999_999)
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $second = SlowTest::fromTestDurationAndMaximumDuration(
            new Event\Code\Test(
                Example\SleeperTest::class,
                'bar',
                'bar',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $thirdForSameTest = SlowTest::fromTestDurationAndMaximumDuration(
            clone $first->test(),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(0, $first->duration()->seconds() - 1),
                $faker->numberBetween(0, 999_999_999)
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $collector = new DefaultCollector();

        $collector->collect($first);
        $collector->collect($second);
        $collector->collect($thirdForSameTest);

        $expected = [
            $first,
            $second,
        ];

        self::assertSame($expected, $collector->collected());
    }

    public function testCollectReplacesSlowTestWhenDurationIsGreaterThanPreviousSlowTest(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 999_999_999)
        );

        $first = SlowTest::fromTestDurationAndMaximumDuration(
            new Event\Code\Test(
                Example\SleeperTest::class,
                'foo',
                'foo with data set #123',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            ),
            $maximumDuration
        );

        $second = SlowTest::fromTestDurationAndMaximumDuration(
            new Event\Code\Test(
                Example\SleeperTest::class,
                'bar',
                'bar',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            ),
            $maximumDuration
        );

        $thirdForSameTest = SlowTest::fromTestDurationAndMaximumDuration(
            clone $first->test(),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween($first->duration()->seconds() + 1),
                $faker->numberBetween(0, 999_999_999)
            ),
            $maximumDuration
        );

        $collector = new DefaultCollector();

        $collector->collect($first);
        $collector->collect($second);
        $collector->collect($thirdForSameTest);

        $expected = [
            $thirdForSameTest,
            $second,
        ];

        self::assertSame($expected, $collector->collected());
    }
}
