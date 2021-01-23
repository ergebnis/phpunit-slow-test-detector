<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-collector
 */

namespace Ergebnis\PHPUnit\SlowTestCollector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestCollector\SlowTest;
use Ergebnis\PHPUnit\SlowTestCollector\SlowTestCollector;
use Ergebnis\Test\Util;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestCollector\SlowTestCollector
 *
 * @uses \Ergebnis\PHPUnit\SlowTestCollector\SlowTest
 */
final class SlowTestCollectorTest extends Framework\TestCase
{
    use Util\Helper;

    public function testDefaults(): void
    {
        $slowTestCollector = new SlowTestCollector(Event\Telemetry\Duration::fromSeconds(self::faker()->numberBetween()));

        self::assertSame([], $slowTestCollector->slowTests());
    }

    public function testConstructorSetsValues(): void
    {
        $maximumDuration = Event\Telemetry\Duration::fromSeconds(self::faker()->numberBetween());

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        self::assertSame($maximumDuration, $slowTestCollector->maximumDuration());
    }

    public function testCollectIgnoresSlowTestWhenDurationIsEqualToMaximumDuration(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 999999999)
        );

        $slowTest = SlowTest::fromTestAndDuration(
            self::createTest('slowTest'),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $maximumDuration->seconds(),
                $maximumDuration->nanoseconds()
            )
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->collect($slowTest);

        self::assertSame([], $slowTestCollector->slowTests());
    }

    public function testCollectCollectsSlowTestsWhenDurationIsGreaterThanMaximumDuration(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(1, 999999998)
        );

        $slowTest = SlowTest::fromTestAndDuration(
            self::createTest('slowTest'),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $maximumDuration->seconds(),
                $maximumDuration->nanoseconds() + 1,
            )
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->collect($slowTest);

        $expected = [
            $slowTest,
        ];

        self::assertSame($expected, $slowTestCollector->slowTests());
    }

    public function testCollectIgnoresSlowTestWhenDurationIsGreaterThanMaximumDurationButLessThanDurationForEqualTest(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(1, 99999997),
        );

        $slowTest = SlowTest::fromTestAndDuration(
            self::createTest('slowTest'),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $maximumDuration->seconds(),
                $maximumDuration->nanoseconds() + 2
            )
        );

        $test = $slowTest->test();
        $duration = $slowTest->duration();

        $slowTestForEqualTestWithLessDuration = SlowTest::fromTestAndDuration(
            new Event\Code\Test(
                $test->className(),
                $test->methodName(),
                $test->methodNameWithDataSet()
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $duration->seconds(),
                $duration->nanoseconds() - 1,
            )
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->collect($slowTest);
        $slowTestCollector->collect($slowTestForEqualTestWithLessDuration);

        $expected = [
            $slowTest,
        ];

        self::assertSame($expected, $slowTestCollector->slowTests());
    }

    public function testCollectIgnoresSlowTestWhenDurationIsGreaterThanMaximumDurationButEqualToDurationForSameTest(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 99999998),
        );

        $slowTest = SlowTest::fromTestAndDuration(
            self::createTest('slowTest'),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $maximumDuration->seconds(),
                $maximumDuration->nanoseconds() + 1
            )
        );

        $test = $slowTest->test();
        $duration = $slowTest->duration();

        $slowTestForEqualTestWithEqualDuration = SlowTest::fromTestAndDuration(
            new Event\Code\Test(
                $test->className(),
                $test->methodName(),
                $test->methodNameWithDataSet()
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $duration->seconds(),
                $duration->nanoseconds()
            )
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->collect($slowTest);
        $slowTestCollector->collect($slowTestForEqualTestWithEqualDuration);

        $expected = [
            $slowTest,
        ];
        self::assertSame($expected, $slowTestCollector->slowTests());
    }

    public function testCollectReplacesSlowTestWhenDurationIsGreaterThanMaximumDurationAndGreaterThanDurationForSameTest(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 99999997),
        );

        $slowTest = SlowTest::fromTestAndDuration(
            self::createTest('slowTest'),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $maximumDuration->seconds(),
                $maximumDuration->nanoseconds() + 1
            )
        );

        $test = $slowTest->test();
        $duration = $slowTest->duration();

        $slowTestForEqualTestWithGreaterDuration = SlowTest::fromTestAndDuration(
            new Event\Code\Test(
                $test->className(),
                $test->methodName(),
                $test->methodNameWithDataSet()
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $duration->seconds(),
                $duration->nanoseconds() + 1
            )
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->collect($slowTest);
        $slowTestCollector->collect($slowTestForEqualTestWithGreaterDuration);

        $expected = [
            $slowTestForEqualTestWithGreaterDuration,
        ];

        self::assertSame($expected, $slowTestCollector->slowTests());
    }

    public function testCollectCollectsMultipleSlowTestsWhenDurationIsGreaterThanMaximumDuration(): void
    {
        $faker = self::faker()->unique();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(2),
            $faker->numberBetween(500000000, 999999999)
        );

        $one = SlowTest::fromTestAndDuration(
            self::createTest('one'),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $maximumDuration->seconds() - 1,
                $maximumDuration->nanoseconds()
            )
        );

        $two = SlowTest::fromTestAndDuration(
            self::createTest('two'),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $maximumDuration->seconds(),
                $maximumDuration->nanoseconds() - 1
            )
        );

        $three = SlowTest::fromTestAndDuration(
            self::createTest('three'),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $maximumDuration->seconds(),
                $maximumDuration->nanoseconds()
            )
        );

        $four = SlowTest::fromTestAndDuration(
            self::createTest('four'),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $maximumDuration->seconds(),
                $maximumDuration->nanoseconds() + 1
            )
        );

        $five = SlowTest::fromTestAndDuration(
            self::createTest('five'),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $maximumDuration->seconds() + 1,
                $maximumDuration->nanoseconds()
            )
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->collect($one);
        $slowTestCollector->collect($two);
        $slowTestCollector->collect($three);
        $slowTestCollector->collect($four);
        $slowTestCollector->collect($five);

        $expected = [
            $four,
            $five,
        ];

        self::assertSame($expected, $slowTestCollector->slowTests());
    }

    private static function createTest(string $methodName): Event\Code\Test
    {
        $faker = self::faker();

        $methodNameWithDataSet = \sprintf(
            '%s with data set #%d',
            $methodName,
            $faker->numberBetween()
        );

        if ($faker->boolean) {
            $methodNameWithDataSet = $methodName;
        }

        return new Event\Code\Test(
            self::class,
            $methodName,
            $methodNameWithDataSet
        );
    }
}
