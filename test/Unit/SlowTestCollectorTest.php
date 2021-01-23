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

    public function testDoesNotCollectSlowTestWhenTestHasBeenPreparedButHasNotPassed(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 500000000)
        );

        $test = self::createTest('test');

        $preparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testHasBeenPrepared(
            $test,
            $preparedTime
        );

        self::assertSame([], $slowTestCollector->slowTests());
    }

    public function testDoesNotCollectSlowTestWhenTestHasPassedButNotBeenPrepared(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 500000000)
        );

        $test = self::createTest('test');

        $passedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testHasPassed(
            $test,
            $passedTime
        );

        self::assertSame([], $slowTestCollector->slowTests());
    }

    public function testDoesNotCollectSlowTestWhenTestHasBeenPreparedAndPassedWithDurationLessThanMaximumDuration(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(1, 500000000)
        );

        $test = self::createTest('test');

        $preparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $passedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $preparedTime->seconds() + $maximumDuration->seconds(),
            $preparedTime->nanoseconds() + $maximumDuration->nanoseconds() - 1
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testHasBeenPrepared(
            $test,
            $preparedTime
        );

        $slowTestCollector->testHasPassed(
            $test,
            $passedTime
        );

        self::assertSame([], $slowTestCollector->slowTests());
    }

    public function testDoesNotCollectSlowTestWhenTestHasBeenPreparedAndPassedWithDurationEqualToMaximumDuration(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 500000000)
        );

        $test = self::createTest('test');

        $preparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $passedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $preparedTime->seconds() + $maximumDuration->seconds(),
            $preparedTime->nanoseconds() + $maximumDuration->nanoseconds()
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testHasBeenPrepared(
            $test,
            $preparedTime
        );

        $slowTestCollector->testHasPassed(
            $test,
            $passedTime
        );

        self::assertSame([], $slowTestCollector->slowTests());
    }

    public function testCollectsSlowTestWhenTestHasBeenPreparedAndPassedWithDurationGreaterThanMaximumDuration(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 500000000)
        );

        $test = self::createTest('test');

        $preparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $passedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $preparedTime->seconds() + $maximumDuration->seconds(),
            $preparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 1
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testHasBeenPrepared(
            $test,
            $preparedTime
        );

        $slowTestCollector->testHasPassed(
            $test,
            $passedTime
        );

        $expected = [
            SlowTest::fromTestAndDuration(
                $test,
                $passedTime->duration($preparedTime)
            ),
        ];

        self::assertEquals($expected, $slowTestCollector->slowTests());
    }

    public function testDoesNotReplaceSlowTestWhenDurationIsGreaterThanMaximumDurationButLessThanPreviousDurationForSameTest(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 500000000)
        );

        $test = self::createTest('test');

        $firstPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $firstPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $firstPreparedTime->seconds() + $maximumDuration->seconds(),
            $firstPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 2
        );

        $secondPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $secondPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $secondPreparedTime->seconds() + $maximumDuration->seconds(),
            $secondPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 1
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testHasBeenPrepared(
            $test,
            $firstPreparedTime
        );

        $slowTestCollector->testHasPassed(
            $test,
            $firstPassedTime
        );

        $slowTestCollector->testHasBeenPrepared(
            $test,
            $secondPreparedTime
        );

        $slowTestCollector->testHasPassed(
            $test,
            $secondPassedTime
        );

        $expected = [
            SlowTest::fromTestAndDuration(
                $test,
                $firstPassedTime->duration($firstPreparedTime)
            ),
        ];

        self::assertEquals($expected, $slowTestCollector->slowTests());
    }

    public function testDoesNotReplaceSlowTestWhenDurationIsGreaterThanMaximumDurationAndTestHasPassedButNotBeenPrepared(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 500000000)
        );

        $test = self::createTest('test');

        $firstPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $firstPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $firstPreparedTime->seconds() + $maximumDuration->seconds(),
            $firstPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 1
        );

        $secondPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $firstPreparedTime->seconds() + $maximumDuration->seconds(),
            $firstPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 2
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testHasBeenPrepared(
            $test,
            $firstPreparedTime
        );

        $slowTestCollector->testHasPassed(
            $test,
            $firstPassedTime
        );

        $slowTestCollector->testHasPassed(
            $test,
            $secondPassedTime
        );

        $expected = [
            SlowTest::fromTestAndDuration(
                $test,
                $firstPassedTime->duration($firstPreparedTime)
            ),
        ];

        self::assertEquals($expected, $slowTestCollector->slowTests());
    }

    public function testReplacesSlowTestWhenDurationIsGreaterThanMaximumDurationAndGreaterThanPreviousDurationForSameTest(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 500000000)
        );

        $test = self::createTest('test');

        $firstPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $firstPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $firstPreparedTime->seconds() + $maximumDuration->seconds(),
            $firstPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 1
        );

        $secondPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $secondPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $secondPreparedTime->seconds() + $maximumDuration->seconds(),
            $secondPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 2
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testHasBeenPrepared(
            $test,
            $firstPreparedTime
        );

        $slowTestCollector->testHasPassed(
            $test,
            $firstPassedTime
        );

        $slowTestCollector->testHasBeenPrepared(
            $test,
            $secondPreparedTime
        );

        $slowTestCollector->testHasPassed(
            $test,
            $secondPassedTime
        );

        $expected = [
            SlowTest::fromTestAndDuration(
                $test,
                $secondPassedTime->duration($secondPreparedTime)
            ),
        ];

        self::assertEquals($expected, $slowTestCollector->slowTests());
    }

    public function testCollectsMultipleSlowTestsWhenDurationIsGreaterThanMaximumDuration(): void
    {
        $faker = self::faker()->unique();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(250000000, 500000000)
        );

        $firstTest = self::createTest('one');

        $firstPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $firstPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $firstPreparedTime->seconds() + $maximumDuration->seconds() - 1,
            $firstPreparedTime->nanoseconds() + $maximumDuration->nanoseconds()
        );

        $secondTest = self::createTest('two');

        $secondPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $secondPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $secondPreparedTime->seconds() + $maximumDuration->seconds(),
            $secondPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() - 1
        );

        $thirdTest = self::createTest('three');

        $thirdPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $thirdPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $thirdPreparedTime->seconds() + $maximumDuration->seconds(),
            $thirdPreparedTime->nanoseconds() + $maximumDuration->nanoseconds()
        );

        $fourthTest = self::createTest('four');

        $fourthPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $fourthPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $fourthPreparedTime->seconds() + $maximumDuration->seconds(),
            $fourthPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 1
        );

        $fifthTest = self::createTest('five');

        $fifthPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $fifthPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $fifthPreparedTime->seconds() + $maximumDuration->seconds() + 1,
            $fifthPreparedTime->nanoseconds() + $maximumDuration->nanoseconds()
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testHasBeenPrepared(
            $firstTest,
            $firstPreparedTime
        );

        $slowTestCollector->testHasPassed(
            $firstTest,
            $firstPassedTime
        );

        $slowTestCollector->testHasBeenPrepared(
            $secondTest,
            $secondPreparedTime
        );

        $slowTestCollector->testHasPassed(
            $secondTest,
            $secondPassedTime
        );

        $slowTestCollector->testHasBeenPrepared(
            $thirdTest,
            $thirdPreparedTime
        );

        $slowTestCollector->testHasPassed(
            $thirdTest,
            $thirdPassedTime
        );

        $slowTestCollector->testHasBeenPrepared(
            $fourthTest,
            $fourthPreparedTime
        );

        $slowTestCollector->testHasPassed(
            $fourthTest,
            $fourthPassedTime
        );

        $slowTestCollector->testHasBeenPrepared(
            $fifthTest,
            $fifthPreparedTime
        );

        $slowTestCollector->testHasPassed(
            $fifthTest,
            $fifthPassedTime
        );

        $expected = [
            SlowTest::fromTestAndDuration(
                $fourthTest,
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    $maximumDuration->seconds(),
                    $maximumDuration->nanoseconds() + 1
                )
            ),
            SlowTest::fromTestAndDuration(
                $fifthTest,
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    $maximumDuration->seconds() + 1,
                    $maximumDuration->nanoseconds()
                )
            ),
        ];

        self::assertEquals($expected, $slowTestCollector->slowTests());
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
