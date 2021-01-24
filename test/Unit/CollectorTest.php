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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Collector;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture;
use Ergebnis\Test\Util;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Collector
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 */
final class CollectorTest extends Framework\TestCase
{
    use Util\Helper;

    public function testDefaults(): void
    {
        $collector = new Collector();

        self::assertSame([], $collector->collected());
    }

    public function testCollectCollectsSlowTests(): void
    {
        $faker = self::faker();

        $first = SlowTest::fromTestAndDuration(
            new Event\Code\Test(
                Fixture\ExampleTest::class,
                'foo',
                'foo with data set #123',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $second = SlowTest::fromTestAndDuration(
            new Event\Code\Test(
                Fixture\ExampleTest::class,
                'bar',
                'bar',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $third = SlowTest::fromTestAndDuration(
            new Event\Code\Test(
                Fixture\ExampleTest::class,
                'baz',
                'baz with data set "string"',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $collector = new Collector();

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

        $first = SlowTest::fromTestAndDuration(
            new Event\Code\Test(
                Fixture\ExampleTest::class,
                'foo',
                'foo with data set #123',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(1),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $second = SlowTest::fromTestAndDuration(
            new Event\Code\Test(
                Fixture\ExampleTest::class,
                'bar',
                'bar',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $thirdForSameTest = SlowTest::fromTestAndDuration(
            new Event\Code\Test(
                $first->test()->className(),
                $first->test()->methodName(),
                $first->test()->methodNameWithDataSet(),
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(0, $first->duration()->seconds() - 1),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $collector = new Collector();

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

        $first = SlowTest::fromTestAndDuration(
            new Event\Code\Test(
                Fixture\ExampleTest::class,
                'foo',
                'foo with data set #123',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $second = SlowTest::fromTestAndDuration(
            new Event\Code\Test(
                Fixture\ExampleTest::class,
                'bar',
                'bar',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $thirdForSameTest = SlowTest::fromTestAndDuration(
            new Event\Code\Test(
                $first->test()->className(),
                $first->test()->methodName(),
                $first->test()->methodNameWithDataSet(),
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween($first->duration()->seconds() + 1),
                $faker->numberBetween(0, 999_999_999)
            )
        );

        $collector = new Collector();

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
