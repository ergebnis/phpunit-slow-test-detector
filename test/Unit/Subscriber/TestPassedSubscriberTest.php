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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Subscriber;

use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\Subscriber\TestPassedSubscriber;
use Ergebnis\PHPUnit\SlowTestDetector\Test\Double;
use Ergebnis\PHPUnit\SlowTestDetector\Test\Example;
use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use Ergebnis\Test\Util;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Subscriber\TestPassedSubscriber
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper
 */
final class TestPassedSubscriberTest extends Framework\TestCase
{
    use Util\Helper;

    public function testNotifyDoesNotCollectSlowTestWhenDurationIsLessThanMaximumDuration(): void
    {
        $faker = self::faker();

        $maximumDuration = MaximumDuration::fromSeconds($faker->numberBetween(
            5,
            10
        ));

        $preparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(1, 999_999_999)
        );

        $preparedTest = new Event\Code\Test(
            Example\SleeperTest::class,
            'foo',
            'foo with data set #123'
        );

        $passedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $preparedTime->seconds() + $maximumDuration->toTelemetryDuration()->seconds(),
            $preparedTime->nanoseconds() - 1
        );

        $passedTest = new Event\Code\Test(
            Example\SleeperTest::class,
            'foo',
            'foo with data set #123'
        );

        $passedTestEvent = new Event\Test\Passed(
            new Event\Telemetry\Info(
                new Event\Telemetry\Snapshot(
                    $passedTime,
                    Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween()),
                    Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween())
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    $faker->numberBetween(),
                    $faker->numberBetween(0, 999_999_999)
                ),
                Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween()),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    $faker->numberBetween(),
                    $faker->numberBetween(0, 999_999_999)
                ),
                Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween()),
            ),
            $passedTest
        );

        $timeKeeper = new TimeKeeper();

        $timeKeeper->start(
            $preparedTest,
            $preparedTime
        );

        $collector = new Double\Collector\AppendingCollector();

        $subscriber = new TestPassedSubscriber(
            $maximumDuration,
            $timeKeeper,
            $collector
        );

        $subscriber->notify($passedTestEvent);

        self::assertSame([], $collector->collected());
    }

    public function testNotifyDoesNotCollectSlowTestWhenDurationIsEqualToMaximumDuration(): void
    {
        $faker = self::faker();

        $maximumDuration = MaximumDuration::fromSeconds($faker->numberBetween(
            5,
            10
        ));

        $preparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(1, 999_999_999)
        );

        $preparedTest = new Event\Code\Test(
            Example\SleeperTest::class,
            'foo',
            'foo with data set #123'
        );

        $passedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $preparedTime->seconds() + $maximumDuration->toTelemetryDuration()->seconds(),
            $preparedTime->nanoseconds()
        );

        $passedTest = clone $preparedTest;

        $passedTestEvent = new Event\Test\Passed(
            new Event\Telemetry\Info(
                new Event\Telemetry\Snapshot(
                    $passedTime,
                    Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween()),
                    Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween())
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    $faker->numberBetween(),
                    $faker->numberBetween(0, 999_999_999)
                ),
                Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween()),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    $faker->numberBetween(),
                    $faker->numberBetween(0, 999_999_999)
                ),
                Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween()),
            ),
            $passedTest
        );

        $timeKeeper = new TimeKeeper();

        $timeKeeper->start(
            $preparedTest,
            $preparedTime
        );

        $collector = new Double\Collector\AppendingCollector();

        $subscriber = new TestPassedSubscriber(
            $maximumDuration,
            $timeKeeper,
            $collector
        );

        $subscriber->notify($passedTestEvent);

        self::assertSame([], $collector->collected());
    }

    public function testNotifyCollectsSlowTestWhenDurationIsGreaterThanMaximumDuration(): void
    {
        $faker = self::faker();

        $maximumDuration = MaximumDuration::fromSeconds($faker->numberBetween(
            5,
            10
        ));

        $preparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 999_999_998)
        );

        $preparedTest = new Event\Code\Test(
            Example\SleeperTest::class,
            'foo',
            'foo with data set #123'
        );

        $passedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $preparedTime->seconds() + $maximumDuration->toTelemetryDuration()->seconds(),
            $preparedTime->nanoseconds() + 1
        );

        $passedTest = clone $preparedTest;

        $passedTestEvent = new Event\Test\Passed(
            new Event\Telemetry\Info(
                new Event\Telemetry\Snapshot(
                    $passedTime,
                    Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween()),
                    Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween())
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    $faker->numberBetween(),
                    $faker->numberBetween(0, 999_999_999)
                ),
                Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween()),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    $faker->numberBetween(),
                    $faker->numberBetween(0, 999_999_999)
                ),
                Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween()),
            ),
            $passedTest
        );

        $timeKeeper = new TimeKeeper();

        $timeKeeper->start(
            $preparedTest,
            $preparedTime
        );

        $collector = new Double\Collector\AppendingCollector();

        $subscriber = new TestPassedSubscriber(
            $maximumDuration,
            $timeKeeper,
            $collector
        );

        $subscriber->notify($passedTestEvent);

        $expected = [
            SlowTest::fromTestDurationAndMaximumDuration(
                $passedTest,
                $passedTime->duration($preparedTime),
                $maximumDuration->toTelemetryDuration()
            ),
        ];

        self::assertEquals($expected, $collector->collected());
    }
}
