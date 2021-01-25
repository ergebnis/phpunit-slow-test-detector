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

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Formatter\ToMillisecondsDurationFormatter;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter\DefaultReporter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\Test\Example;
use Ergebnis\Test\Util;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\DefaultReporter
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\MaximumNumberNotGreaterThanZero
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Formatter\ToMillisecondsDurationFormatter
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 */
final class DefaultReporterTest extends Framework\TestCase
{
    use Util\Helper;

    /**
     * @dataProvider \Ergebnis\Test\Util\DataProvider\IntProvider::lessThanZero()
     * @dataProvider \Ergebnis\Test\Util\DataProvider\IntProvider::zero()
     */
    public function testConstructorRejectsMaximumCountLessThanOne(int $maximumCount): void
    {
        $faker = self::faker();

        $durationFormatter = $this->createMock(Event\Telemetry\DurationFormatter::class);
        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 999999999)
        );

        $this->expectException(Exception\MaximumNumberNotGreaterThanZero::class);

        new DefaultReporter(
            $durationFormatter,
            $maximumDuration,
            $maximumCount
        );
    }

    public function testReportReturnsEmptyStringWhenNoSlowTestsHaveBeenSpecified(): void
    {
        $faker = self::faker();

        $durationFormatter = $this->createMock(Event\Telemetry\DurationFormatter::class);
        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 999999999)
        );
        $maximumCount = $faker->numberBetween();

        $reporter = new DefaultReporter(
            $durationFormatter,
            $maximumDuration,
            $maximumCount
        );

        $report = $reporter->report();

        self::assertSame('', $report);
    }

    public function testReportReturnsReportWhenTheNumberOfSlowTestsIsSmallerThanTheMaximumCount(): void
    {
        $faker = self::faker();

        $slowTests = [
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'foo',
                    'foo with data set #123',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    7,
                    890_123_456
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'bar',
                    'bar',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    12,
                    345_000_000
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'baz',
                    'baz with dataset "string"',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    123_000_000
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'qux',
                    'qux',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    3,
                    456_000_000
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'quz',
                    'quz',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    1,
                    234_000_000
                )
            ),
        ];

        $durationFormatter = new ToMillisecondsDurationFormatter();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            0,
            100_000_000
        );

        $maximumNumber = $faker->numberBetween(\count($slowTests) + 1);

        $reporter = new DefaultReporter(
            $durationFormatter,
            $maximumDuration,
            $maximumNumber
        );

        $report = $reporter->report(...$slowTests);

        $expected = <<<'TXT'
Detected 5 tests that took longer than 100 ms.

12,345 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::bar
 7,890 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::foo with data set #123
 3,456 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::qux
 1,234 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::quz
   123 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::baz with dataset "string"

TXT;

        self::assertSame($expected, $report);
    }

    public function testReportReturnsReportWhenTheNumberOfSlowTestsIsEqualToTheMaximumCount(): void
    {
        $slowTests = [
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'foo',
                    'foo with data set #123',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    7,
                    890_123_456
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'bar',
                    'bar',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    12,
                    345_000_000
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'baz',
                    'baz with dataset "string"',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    123_000_000
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'qux',
                    'qux',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    3,
                    456_000_000
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'quz',
                    'quz',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    1,
                    234_000_000
                )
            ),
        ];

        $durationFormatter = new ToMillisecondsDurationFormatter();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            0,
            100_000_000
        );

        $maximumNumber = \count($slowTests);

        $reporter = new DefaultReporter(
            $durationFormatter,
            $maximumDuration,
            $maximumNumber
        );

        $report = $reporter->report(...$slowTests);

        $expected = <<<'TXT'
Detected 5 tests that took longer than 100 ms.

12,345 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::bar
 7,890 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::foo with data set #123
 3,456 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::qux
 1,234 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::quz
   123 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::baz with dataset "string"

TXT;

        self::assertSame($expected, $report);
    }

    public function testReportReturnsReportWhenTheNumberOfSlowTestsIsOneMoreThanTheMaximumCount(): void
    {
        $slowTests = [
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'foo',
                    'foo with data set #123',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    7,
                    890_123_456
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'bar',
                    'bar',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    12,
                    345_000_000
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'baz',
                    'baz with dataset "string"',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    123_000_000
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'qux',
                    'qux',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    3,
                    456_000_000
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'quz',
                    'quz',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    1,
                    234_000_000
                )
            ),
        ];

        $durationFormatter = new ToMillisecondsDurationFormatter();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            0,
            100_000_000
        );

        $maximumNumber = \count($slowTests) - 1;

        $reporter = new DefaultReporter(
            $durationFormatter,
            $maximumDuration,
            $maximumNumber
        );

        $report = $reporter->report(...$slowTests);

        $expected = <<<'TXT'
Detected 5 tests that took longer than 100 ms.

12,345 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::bar
 7,890 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::foo with data set #123
 3,456 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::qux
 1,234 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::quz

There is one additional slow test that is not listed here.
TXT;

        self::assertSame($expected, $report);
    }

    public function testReportReturnsReportWhenTheNumberOfSlowTestsIsGreaterThanTheMaximumCountPlusOne(): void
    {
        $slowTests = [
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'foo',
                    'foo with data set #123',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    7,
                    890_123_456
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'bar',
                    'bar',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    12,
                    345_000_000
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'baz',
                    'baz with dataset "string"',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    123_000_000
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'qux',
                    'qux',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    3,
                    456_000_000
                )
            ),
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Example\SleeperTest::class,
                    'quz',
                    'quz',
                ),
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    1,
                    234_000_000
                )
            ),
        ];

        $durationFormatter = new ToMillisecondsDurationFormatter();

        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            0,
            100_000_000
        );

        $maximumNumber = \count($slowTests) - 2;

        $reporter = new DefaultReporter(
            $durationFormatter,
            $maximumDuration,
            $maximumNumber
        );

        $report = $reporter->report(...$slowTests);

        $expected = <<<'TXT'
Detected 5 tests that took longer than 100 ms.

12,345 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::bar
 7,890 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::foo with data set #123
 3,456 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::qux

There are 2 additional slow tests that are not listed here.
TXT;

        self::assertSame($expected, $report);
    }
}
