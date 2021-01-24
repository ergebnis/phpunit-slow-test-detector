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

use Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture;
use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use Ergebnis\Test\Util;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper
 */
final class TimeKeeperTest extends Framework\TestCase
{
    use Util\Helper;

    public function testStopReturnsZeroDurationWhenTestHasNotBeenStoppedButNotStarted(): void
    {
        $faker = self::faker();

        $stoppedTest = new Event\Code\Test(
            Fixture\ExampleTest::class,
            'foo',
            'foo with data set #123'
        );

        $stoppedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 999_999_999),
        );

        $timeKeeper = new TimeKeeper();

        $duration = $timeKeeper->stop(
            $stoppedTest,
            $stoppedTime
        );

        self::assertEquals(Event\Telemetry\Duration::fromSeconds(0), $duration);
    }

    public function testStopReturnsDurationWhenTestHasBeenStartedAndStopped(): void
    {
        $faker = self::faker();

        $startedTest = new Event\Code\Test(
            Fixture\ExampleTest::class,
            'foo',
            'foo with data set #123'
        );

        $startedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 999_999_999),
        );

        $stoppedTest = new Event\Code\Test(
            $startedTest->className(),
            $startedTest->methodName(),
            $startedTest->methodNameWithDataSet(),
        );

        $stoppedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween($startedTime->seconds() + 1),
            $faker->numberBetween(0, 999_999_999),
        );

        $timeKeeper = new TimeKeeper();

        $timeKeeper->start(
            $startedTest,
            $startedTime
        );

        $duration = $timeKeeper->stop(
            $stoppedTest,
            $stoppedTime
        );

        self::assertEquals($stoppedTime->duration($startedTime), $duration);
    }

    public function testStopReturnsZeroDurationWhenTestHasBeenStartedAndStoppedAndStoppedAgain(): void
    {
        $faker = self::faker();

        $startedTest = new Event\Code\Test(
            Fixture\ExampleTest::class,
            'foo',
            'foo with data set #123'
        );

        $startedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 999_999_999),
        );

        $firstStoppedTest = new Event\Code\Test(
            $startedTest->className(),
            $startedTest->methodName(),
            $startedTest->methodNameWithDataSet(),
        );

        $firstStoppedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween($startedTime->seconds() + 1),
            $faker->numberBetween(0, 999_999_999),
        );

        $secondStoppedTest = new Event\Code\Test(
            $startedTest->className(),
            $startedTest->methodName(),
            $startedTest->methodNameWithDataSet(),
        );

        $secondStoppedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween($startedTime->seconds() + 1),
            $faker->numberBetween(0, 999_999_999),
        );

        $timeKeeper = new TimeKeeper();

        $timeKeeper->start(
            $startedTest,
            $startedTime
        );

        $timeKeeper->stop(
            $firstStoppedTest,
            $firstStoppedTime
        );

        $duration = $timeKeeper->stop(
            $secondStoppedTest,
            $secondStoppedTime
        );

        self::assertEquals(Event\Telemetry\Duration::fromSeconds(0), $duration);
    }

    public function testCanStartAndStopMultipleTests(): void
    {
        $faker = self::faker();

        $firstStartedTest = new Event\Code\Test(
            Fixture\ExampleTest::class,
            'foo',
            'foo with data set #123'
        );

        $firstStartedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 999_999_999),
        );

        $firstStoppedTest = new Event\Code\Test(
            $firstStartedTest->className(),
            $firstStartedTest->methodName(),
            $firstStartedTest->methodNameWithDataSet(),
        );

        $firstStoppedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween($firstStartedTime->seconds() + 1),
            $faker->numberBetween(0, 999_999_999),
        );

        $secondStartedTest = new Event\Code\Test(
            Fixture\ExampleTest::class,
            'bar',
            'bar'
        );

        $secondStartedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 999_999_999),
        );

        $secondStoppedTest = new Event\Code\Test(
            $secondStartedTest->className(),
            $secondStartedTest->methodName(),
            $secondStartedTest->methodNameWithDataSet(),
        );

        $secondStoppedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween($secondStartedTime->seconds() + 1),
            $faker->numberBetween(0, 999_999_999),
        );

        $timeKeeper = new TimeKeeper();

        $timeKeeper->start(
            $firstStartedTest,
            $firstStartedTime
        );

        $timeKeeper->start(
            $secondStartedTest,
            $secondStartedTime
        );

        $secondDuration = $timeKeeper->stop(
            $secondStoppedTest,
            $secondStoppedTime
        );

        $firstDuration = $timeKeeper->stop(
            $firstStoppedTest,
            $firstStoppedTime
        );

        self::assertEquals($firstStoppedTime->duration($firstStartedTime), $firstDuration);
        self::assertEquals($secondStoppedTime->duration($secondStartedTime), $secondDuration);
    }
}
