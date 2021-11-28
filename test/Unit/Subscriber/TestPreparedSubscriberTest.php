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

use Ergebnis\PHPUnit\SlowTestDetector\Subscriber;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Subscriber\TestPreparedSubscriber
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper
 */
final class TestPreparedSubscriberTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNotifyRecordsTestStartWithTimeKeeper(): void
    {
        $faker = self::faker();

        $preparedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 999_999_999),
        );

        $preparedTest = new Event\Code\Test(
            Test\Example\SleeperTest::class,
            'foo',
            'foo with data set #123',
        );

        $preparedTestEvent = new Event\Test\Prepared(
            new Event\Telemetry\Info(
                new Event\Telemetry\Snapshot(
                    $preparedTime,
                    Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween()),
                    Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween()),
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    $faker->numberBetween(),
                    $faker->numberBetween(0, 999_999_999),
                ),
                Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween()),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    $faker->numberBetween(),
                    $faker->numberBetween(0, 999_999_999),
                ),
                Event\Telemetry\MemoryUsage::fromBytes($faker->numberBetween()),
            ),
            $preparedTest,
        );

        $timeKeeper = new TimeKeeper();

        $subscriber = new Subscriber\TestPreparedSubscriber($timeKeeper);

        $subscriber->notify($preparedTestEvent);

        $passedTime = Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
            $faker->numberBetween($preparedTime->seconds()),
            0,
        );

        $duration = $timeKeeper->stop(
            clone $preparedTest,
            $passedTime,
        );

        self::assertEquals($passedTime->duration($preparedTime), $duration);
    }
}
