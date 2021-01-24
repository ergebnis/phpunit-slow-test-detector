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
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestCollector;
use Ergebnis\PHPUnit\SlowTestDetector\Test\Double;
use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use Ergebnis\Test\Util;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\SlowTestCollector
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper
 */
final class SlowTestCollectorTest extends Framework\TestCase
{
    use Util\Helper;

    public function testDefaults(): void
    {
        $maximumDuration = Event\Telemetry\Duration::fromSeconds(self::faker()->numberBetween());

        $slowTestCollector = new SlowTestCollector(
            $maximumDuration,
            new TimeKeeper(),
            new Double\Collector\AppendingCollector()
        );

        self::assertSame([], $slowTestCollector->slowTests());
    }

    public function testConstructorSetsValues(): void
    {
        $maximumDuration = Event\Telemetry\Duration::fromSeconds(self::faker()->numberBetween());

        $slowTestCollector = new SlowTestCollector(
            $maximumDuration,
            new TimeKeeper(),
            new Double\Collector\AppendingCollector()
        );

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

        $slowTestCollector = new SlowTestCollector(
            $maximumDuration,
            new TimeKeeper(),
            $this->createMock(Collector\Collector::class)
        );

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

        $slowTestCollector = new SlowTestCollector(
            $maximumDuration,
            new TimeKeeper(),
            new Double\Collector\AppendingCollector()
        );

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

        $slowTestCollector = new SlowTestCollector(
            $maximumDuration,
            new TimeKeeper(),
            new Double\Collector\AppendingCollector()
        );

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

        $slowTestCollector = new SlowTestCollector(
            $maximumDuration,
            new TimeKeeper(),
            new Double\Collector\AppendingCollector()
        );

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

        $slowTestCollector = new SlowTestCollector(
            $maximumDuration,
            new TimeKeeper(),
            new Double\Collector\AppendingCollector()
        );

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
