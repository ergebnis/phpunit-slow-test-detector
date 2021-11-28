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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Reporter;

use Ergebnis\PHPUnit\SlowTestDetector\Formatter;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\DefaultReporter
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Console\Color
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidMaximumCount
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Formatter\ToMillisecondsDurationFormatter
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumCount
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 */
final class DefaultReporterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testReportReturnsEmptyStringWhenNoSlowTestsHaveBeenSpecified(): void
    {
        $faker = self::faker();

        $durationFormatter = $this->createMock(Event\Telemetry\DurationFormatter::class);
        $maximumDuration = MaximumDuration::fromMilliseconds($faker->numberBetween());
        $maximumCount = MaximumCount::fromInt($faker->numberBetween(1));

        $reporter = new Reporter\DefaultReporter(
            $durationFormatter,
            $maximumDuration,
            $maximumCount,
        );

        $report = $reporter->report();

        self::assertSame('', $report);
    }

    public function testReportReturnsReportWhenTheNumberOfSlowTestsIsSmallerThanTheMaximumCountAndLessThanOne(): void
    {
        $maximumDuration = MaximumDuration::fromMilliseconds(100);

        $slowTests = [
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'foo',
                    'foo with data set #123',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    7,
                    890_123_456,
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    3,
                    500_000_000,
                ),
            ),
        ];

        $durationFormatter = new Formatter\ToMillisecondsDurationFormatter();

        $maximumCount = MaximumCount::fromInt(\count($slowTests));

        $reporter = new Reporter\DefaultReporter(
            $durationFormatter,
            $maximumDuration,
            $maximumCount,
        );

        $report = $reporter->report(...$slowTests);

        $expected = <<<TXT
Detected 1 test that took longer than expected.

7,890 ms \e[2m(3,500 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::foo with data set #123
TXT;

        self::assertSame($expected, $report);
    }

    public function testReportReturnsReportWhenTheNumberOfSlowTestsIsSmallerThanTheMaximumCountAndGreaterThanOne(): void
    {
        $faker = self::faker();

        $maximumDuration = MaximumDuration::fromMilliseconds(100);

        $slowTests = [
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'foo',
                    'foo with data set #123',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    7,
                    890_123_456,
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    3,
                    500_000_000,
                ),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'bar',
                    'bar',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    12,
                    345_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'baz',
                    'baz with dataset "string"',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    123_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'qux',
                    'qux',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    3,
                    456_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'quz',
                    'quz',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    1,
                    234_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
        ];

        $durationFormatter = new Formatter\ToMillisecondsDurationFormatter();

        $maximumCount = MaximumCount::fromInt($faker->numberBetween(\count($slowTests) + 1));

        $reporter = new Reporter\DefaultReporter(
            $durationFormatter,
            $maximumDuration,
            $maximumCount,
        );

        $report = $reporter->report(...$slowTests);

        $expected = <<<TXT
Detected 5 tests that took longer than expected.

12,345 ms \e[2m(  100 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::bar
 7,890 ms \e[2m(3,500 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::foo with data set #123
 3,456 ms \e[2m(  100 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::qux
 1,234 ms \e[2m(  100 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::quz
   123 ms \e[2m(  100 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::baz with dataset "string"
TXT;

        self::assertSame($expected, $report);
    }

    public function testReportReturnsReportWhenTheNumberOfSlowTestsIsEqualToTheMaximumCount(): void
    {
        $maximumDuration = MaximumDuration::fromMilliseconds(100);

        $slowTests = [
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'foo',
                    'foo with data set #123',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    7,
                    890_123_456,
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    3,
                    500_000_000,
                ),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'bar',
                    'bar',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    12,
                    345_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'baz',
                    'baz with dataset "string"',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    123_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'qux',
                    'qux',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    3,
                    456_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'quz',
                    'quz',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    1,
                    234_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
        ];

        $durationFormatter = new Formatter\ToMillisecondsDurationFormatter();

        $maximumCount = MaximumCount::fromInt(\count($slowTests));

        $reporter = new Reporter\DefaultReporter(
            $durationFormatter,
            $maximumDuration,
            $maximumCount,
        );

        $report = $reporter->report(...$slowTests);

        $expected = <<<TXT
Detected 5 tests that took longer than expected.

12,345 ms \e[2m(  100 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::bar
 7,890 ms \e[2m(3,500 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::foo with data set #123
 3,456 ms \e[2m(  100 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::qux
 1,234 ms \e[2m(  100 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::quz
   123 ms \e[2m(  100 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::baz with dataset "string"
TXT;

        self::assertSame($expected, $report);
    }

    public function testReportReturnsReportWhenTheNumberOfSlowTestsIsOneMoreThanTheMaximumCount(): void
    {
        $maximumDuration = MaximumDuration::fromMilliseconds(100);

        $slowTests = [
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'foo',
                    'foo with data set #123',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    7,
                    890_123_456,
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    3,
                    500_000_000,
                ),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'bar',
                    'bar',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    12,
                    345_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'baz',
                    'baz with dataset "string"',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    123_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'qux',
                    'qux',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    3,
                    456_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'quz',
                    'quz',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    1,
                    234_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
        ];

        $durationFormatter = new Formatter\ToMillisecondsDurationFormatter();

        $maximumCount = MaximumCount::fromInt(\count($slowTests) - 1);

        $reporter = new Reporter\DefaultReporter(
            $durationFormatter,
            $maximumDuration,
            $maximumCount,
        );

        $report = $reporter->report(...$slowTests);

        $expected = <<<TXT
Detected 5 tests that took longer than expected.

12,345 ms \e[2m(  100 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::bar
 7,890 ms \e[2m(3,500 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::foo with data set #123
 3,456 ms \e[2m(  100 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::qux
 1,234 ms \e[2m(  100 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::quz

There is one additional slow test that is not listed here.
TXT;

        self::assertSame($expected, $report);
    }

    public function testReportReturnsReportWhenTheNumberOfSlowTestsIsGreaterThanTheMaximumCountPlusOne(): void
    {
        $maximumDuration = MaximumDuration::fromMilliseconds(100);

        $slowTests = [
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'foo',
                    'foo with data set #123',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    7,
                    890_123_456,
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    3,
                    500_000_000,
                ),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'bar',
                    'bar',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    12,
                    345_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'baz',
                    'baz with dataset "string"',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    123_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'qux',
                    'qux',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    3,
                    456_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
            SlowTest::fromTestDurationAndMaximumDuration(
                new Event\Code\Test(
                    Test\Example\SleeperTest::class,
                    'quz',
                    'quz',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    1,
                    234_000_000,
                ),
                $maximumDuration->toTelemetryDuration(),
            ),
        ];

        $durationFormatter = new Formatter\ToMillisecondsDurationFormatter();

        $maximumCount = MaximumCount::fromInt(\count($slowTests) - 2);

        $reporter = new Reporter\DefaultReporter(
            $durationFormatter,
            $maximumDuration,
            $maximumCount,
        );

        $report = $reporter->report(...$slowTests);

        $expected = <<<TXT
Detected 5 tests that took longer than expected.

12,345 ms \e[2m(  100 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::bar
 7,890 ms \e[2m(3,500 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::foo with data set #123
 3,456 ms \e[2m(  100 ms)\e[22m Ergebnis\\PHPUnit\\SlowTestDetector\\Test\\Example\\SleeperTest::qux

There are 2 additional slow tests that are not listed here.
TXT;

        self::assertSame($expected, $report);
    }
}
