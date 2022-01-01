<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Subscriber;

use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\Subscriber;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Subscriber\TestSuiteFinishedSubscriber
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 */
final class TestSuiteFinishedSubscriberTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNotifyDoesNothingWhenCollectorHasNotCollectedAnything(): void
    {
        $faker = self::faker();

        $finishedTestSuiteEvent = new Event\TestSuite\Finished(
            new Event\Telemetry\Info(
                new Event\Telemetry\Snapshot(
                    Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
                        $faker->numberBetween(),
                        $faker->numberBetween(0, 999_999_999),
                    ),
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
            $faker->word(),
            new Event\TestSuite\Result(
                $faker->numberBetween(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                [],
                [],
            ),
            null,
        );

        $subscriber = new Subscriber\TestSuiteFinishedSubscriber(
            new Test\Double\Collector\AppendingCollector(),
            new Test\Double\Reporter\CountingReporter(),
        );

        \ob_start();

        $subscriber->notify($finishedTestSuiteEvent);

        $output = \ob_get_clean();

        self::assertSame('', $output);
    }

    public function testNotifyDoesNothingWhenReporterHasNotReportedAnything(): void
    {
        $faker = self::faker();

        $first = SlowTest::fromTestDurationAndMaximumDuration(
            new Event\Code\Test(
                Test\Example\SleeperTest::class,
                'foo',
                'foo with data set #123',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999),
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999),
            ),
        );

        $second = SlowTest::fromTestDurationAndMaximumDuration(
            new Event\Code\Test(
                Test\Example\SleeperTest::class,
                'bar',
                'bar',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999),
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999),
            ),
        );

        $third = SlowTest::fromTestDurationAndMaximumDuration(
            new Event\Code\Test(
                Test\Example\SleeperTest::class,
                'baz',
                'baz with data set "string"',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999),
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999),
            ),
        );

        $finishedTestSuiteEvent = new Event\TestSuite\Finished(
            new Event\Telemetry\Info(
                new Event\Telemetry\Snapshot(
                    Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
                        $faker->numberBetween(),
                        $faker->numberBetween(0, 999_999_999),
                    ),
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
            $faker->word(),
            new Event\TestSuite\Result(
                $faker->numberBetween(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                [],
                [],
            ),
            null,
        );

        $collector = new Test\Double\Collector\AppendingCollector();

        $collector->collect($first);
        $collector->collect($second);
        $collector->collect($third);

        $subscriber = new Subscriber\TestSuiteFinishedSubscriber(
            $collector,
            new Test\Double\Reporter\NullReporter(),
        );

        \ob_start();

        $subscriber->notify($finishedTestSuiteEvent);

        $output = \ob_get_clean();

        self::assertSame('', $output);
    }

    public function testNotifyEchosReportWhenReporterHasReportedSomething(): void
    {
        $faker = self::faker();

        $first = SlowTest::fromTestDurationAndMaximumDuration(
            new Event\Code\Test(
                Test\Example\SleeperTest::class,
                'foo',
                'foo with data set #123',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999),
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999),
            ),
        );

        $second = SlowTest::fromTestDurationAndMaximumDuration(
            new Event\Code\Test(
                Test\Example\SleeperTest::class,
                'bar',
                'bar',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999),
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999),
            ),
        );

        $third = SlowTest::fromTestDurationAndMaximumDuration(
            new Event\Code\Test(
                Test\Example\SleeperTest::class,
                'baz',
                'baz with data set "string"',
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999),
            ),
            Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                $faker->numberBetween(),
                $faker->numberBetween(0, 999_999_999),
            ),
        );

        $finishedTestSuiteEvent = new Event\TestSuite\Finished(
            new Event\Telemetry\Info(
                new Event\Telemetry\Snapshot(
                    Event\Telemetry\HRTime::fromSecondsAndNanoseconds(
                        $faker->numberBetween(),
                        $faker->numberBetween(0, 999_999_999),
                    ),
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
            $faker->word(),
            new Event\TestSuite\Result(
                $faker->numberBetween(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                new Event\TestSuite\FailureCollection(),
                [],
                [],
            ),
            null,
        );

        $collector = new Test\Double\Collector\AppendingCollector();

        $collector->collect($first);
        $collector->collect($second);
        $collector->collect($third);

        $reporter = new Test\Double\Reporter\CountingReporter();

        $subscriber = new Subscriber\TestSuiteFinishedSubscriber(
            $collector,
            $reporter,
        );

        \ob_start();

        $subscriber->notify($finishedTestSuiteEvent);

        $output = \ob_get_clean();

        $report = $reporter->report(
            $first,
            $second,
            $third,
        );

        $expected = <<<TXT


{$report}
TXT;

        self::assertSame($expected, $output);
    }
}
