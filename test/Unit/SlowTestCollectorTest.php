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

use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestCollector;
use Ergebnis\Test\Util;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\SlowTestCollector
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
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
            $faker->numberBetween(0, 500_000_000)
        );

        $preparedTest = self::createTest('test');

        $preparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testPrepared(
            $preparedTest,
            $preparedTime
        );

        self::assertSame([], $slowTestCollector->slowTests());
    }

    public function testDoesNotCollectSlowTestWhenTestHasPassedButNotBeenPrepared(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 500_000_000)
        );

        $passedTest = self::createTest('test');

        $passedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testPassed(
            $passedTest,
            $passedTime
        );

        self::assertSame([], $slowTestCollector->slowTests());
    }

    public function testDoesNotCollectSlowTestWhenTestHasBeenPreparedAndPassedWithDurationLessThanMaximumDuration(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(1, 500_000_000)
        );

        $preparedTest = self::createTest('test');

        $preparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $passedTest = clone $preparedTest;

        $passedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $preparedTime->seconds() + $maximumDuration->seconds(),
            $preparedTime->nanoseconds() + $maximumDuration->nanoseconds() - 1
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testPrepared(
            $preparedTest,
            $preparedTime
        );

        $slowTestCollector->testPassed(
            $passedTest,
            $passedTime
        );

        self::assertSame([], $slowTestCollector->slowTests());
    }

    public function testDoesNotCollectSlowTestWhenTestHasBeenPreparedAndPassedWithDurationEqualToMaximumDuration(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 500_000_000)
        );

        $preparedTest = self::createTest('test');

        $preparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $passedTest = clone $preparedTest;

        $passedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $preparedTime->seconds() + $maximumDuration->seconds(),
            $preparedTime->nanoseconds() + $maximumDuration->nanoseconds()
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testPrepared(
            $preparedTest,
            $preparedTime
        );

        $slowTestCollector->testPassed(
            $passedTest,
            $passedTime
        );

        self::assertSame([], $slowTestCollector->slowTests());
    }

    public function testCollectsSlowTestWhenTestHasBeenPreparedAndPassedWithDurationGreaterThanMaximumDuration(): void
    {
        $faker = self::faker();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 500_000_000)
        );

        $preparedTest = self::createTest('test');

        $preparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $passedTest = clone $preparedTest;

        $passedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $preparedTime->seconds() + $maximumDuration->seconds(),
            $preparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 1
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testPrepared(
            $preparedTest,
            $preparedTime
        );

        $slowTestCollector->testPassed(
            $passedTest,
            $passedTime
        );

        $expected = [
            SlowTest::fromTestAndDuration(
                $passedTest,
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
            $faker->numberBetween(0, 500_000_000)
        );

        $firstPreparedTest = self::createTest('test');

        $firstPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $firstPassedTest = clone $firstPreparedTest;

        $firstPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $firstPreparedTime->seconds() + $maximumDuration->seconds(),
            $firstPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 2
        );

        $secondPreparedTest = clone $firstPreparedTest;

        $secondPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $secondPassedTest = clone $firstPreparedTest;

        $secondPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $secondPreparedTime->seconds() + $maximumDuration->seconds(),
            $secondPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 1
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testPrepared(
            $firstPreparedTest,
            $firstPreparedTime
        );

        $slowTestCollector->testPassed(
            $firstPassedTest,
            $firstPassedTime
        );

        $slowTestCollector->testPrepared(
            $secondPreparedTest,
            $secondPreparedTime
        );

        $slowTestCollector->testPassed(
            $secondPassedTest,
            $secondPassedTime
        );

        $expected = [
            SlowTest::fromTestAndDuration(
                $firstPassedTest,
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
            $faker->numberBetween(0, 500_000_000)
        );

        $firstPreparedTest = self::createTest('test');

        $firstPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $firstPassedTest = clone $firstPreparedTest;

        $firstPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $firstPreparedTime->seconds() + $maximumDuration->seconds(),
            $firstPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 1
        );

        $secondPassedTest = clone $firstPreparedTest;

        $secondPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $firstPreparedTime->seconds() + $maximumDuration->seconds(),
            $firstPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 2
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testPrepared(
            $firstPreparedTest,
            $firstPreparedTime
        );

        $slowTestCollector->testPassed(
            $firstPassedTest,
            $firstPassedTime
        );

        $slowTestCollector->testPassed(
            $secondPassedTest,
            $secondPassedTime
        );

        $expected = [
            SlowTest::fromTestAndDuration(
                $firstPassedTest,
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
            $faker->numberBetween(0, 500_000_000)
        );

        $firstPreparedTest = self::createTest('test');

        $firstPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $firstPassedTest = clone $firstPreparedTest;

        $firstPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $firstPreparedTime->seconds() + $maximumDuration->seconds(),
            $firstPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 1
        );

        $secondPreparedTest = clone $firstPreparedTest;

        $secondPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $secondPassedTest = clone $firstPreparedTest;

        $secondPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $secondPreparedTime->seconds() + $maximumDuration->seconds(),
            $secondPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 2
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testPrepared(
            $firstPreparedTest,
            $firstPreparedTime
        );

        $slowTestCollector->testPassed(
            $firstPassedTest,
            $firstPassedTime
        );

        $slowTestCollector->testPrepared(
            $secondPreparedTest,
            $secondPreparedTime
        );

        $slowTestCollector->testPassed(
            $secondPassedTest,
            $secondPassedTime
        );

        $expected = [
            SlowTest::fromTestAndDuration(
                $secondPassedTest,
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
            $faker->numberBetween(250_000_000, 500_000_000)
        );

        $firstPreparedTest = self::createTest('one');

        $firstPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $firstPassedTest = clone $firstPreparedTest;

        $firstPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $firstPreparedTime->seconds() + $maximumDuration->seconds() - 1,
            $firstPreparedTime->nanoseconds() + $maximumDuration->nanoseconds()
        );

        $secondPreparedTest = self::createTest('two');

        $secondPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $secondPassedTest = clone $secondPreparedTest;

        $secondPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $secondPreparedTime->seconds() + $maximumDuration->seconds(),
            $secondPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() - 1
        );

        $thirdPreparedTest = self::createTest('three');

        $thirdPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $thirdPassedTest = clone $thirdPreparedTest;

        $thirdPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $thirdPreparedTime->seconds() + $maximumDuration->seconds(),
            $thirdPreparedTime->nanoseconds() + $maximumDuration->nanoseconds()
        );

        $fourthPreparedTest = self::createTest('four');

        $fourthPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $fourthPassedTest = clone $fourthPreparedTest;

        $fourthPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $fourthPreparedTime->seconds() + $maximumDuration->seconds(),
            $fourthPreparedTime->nanoseconds() + $maximumDuration->nanoseconds() + 1
        );

        $fifthPreparedTest = self::createTest('five');

        $fifthPreparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            0
        );

        $fifthPassedTest = clone $fifthPreparedTest;

        $fifthPassedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $fifthPreparedTime->seconds() + $maximumDuration->seconds() + 1,
            $fifthPreparedTime->nanoseconds() + $maximumDuration->nanoseconds()
        );

        $slowTestCollector = new SlowTestCollector($maximumDuration);

        $slowTestCollector->testPrepared(
            $firstPreparedTest,
            $firstPreparedTime
        );

        $slowTestCollector->testPassed(
            $firstPassedTest,
            $firstPassedTime
        );

        $slowTestCollector->testPrepared(
            $secondPreparedTest,
            $secondPreparedTime
        );

        $slowTestCollector->testPassed(
            $secondPassedTest,
            $secondPassedTime
        );

        $slowTestCollector->testPrepared(
            $thirdPreparedTest,
            $thirdPreparedTime
        );

        $slowTestCollector->testPassed(
            $thirdPassedTest,
            $thirdPassedTime
        );

        $slowTestCollector->testPrepared(
            $fourthPreparedTest,
            $fourthPreparedTime
        );

        $slowTestCollector->testPassed(
            $fourthPassedTest,
            $fourthPassedTime
        );

        $slowTestCollector->testPrepared(
            $fifthPreparedTest,
            $fifthPreparedTime
        );

        $slowTestCollector->testPassed(
            $fifthPassedTest,
            $fifthPassedTime
        );

        $expected = [
            SlowTest::fromTestAndDuration(
                $fourthPassedTest,
                $fourthPassedTime->duration($fourthPreparedTime)
            ),
            SlowTest::fromTestAndDuration(
                $fifthPassedTest,
                $fifthPassedTime->duration($fifthPreparedTime)
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
