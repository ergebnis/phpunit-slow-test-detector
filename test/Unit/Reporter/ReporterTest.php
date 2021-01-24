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
use Ergebnis\PHPUnit\SlowTestDetector\Reporter\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture;
use Ergebnis\Test\Util;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Reporter
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\MaximumNumberNotGreaterThanZero
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Formatter\ToMillisecondsDurationFormatter
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 */
final class ReporterTest extends Framework\TestCase
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

        new Reporter(
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

        $reporter = new Reporter(
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
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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

        $reporter = new Reporter(
            $durationFormatter,
            $maximumDuration,
            $maximumNumber
        );

        $report = $reporter->report(...$slowTests);

        $expected = <<<'TXT'
Detected 5 tests that took longer than 100 ms.

12,345 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::bar
 7,890 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::foo with data set #123
 3,456 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::qux
 1,234 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::quz
   123 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::baz with dataset "string"

TXT;

        self::assertSame($expected, $report);
    }

    public function testReportReturnsReportWhenTheNumberOfSlowTestsIsEqualToTheMaximumCount(): void
    {
        $slowTests = [
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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

        $reporter = new Reporter(
            $durationFormatter,
            $maximumDuration,
            $maximumNumber
        );

        $report = $reporter->report(...$slowTests);

        $expected = <<<'TXT'
Detected 5 tests that took longer than 100 ms.

12,345 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::bar
 7,890 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::foo with data set #123
 3,456 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::qux
 1,234 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::quz
   123 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::baz with dataset "string"

TXT;

        self::assertSame($expected, $report);
    }

    public function testReportReturnsReportWhenTheNumberOfSlowTestsIsOneMoreThanTheMaximumCount(): void
    {
        $slowTests = [
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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

        $reporter = new Reporter(
            $durationFormatter,
            $maximumDuration,
            $maximumNumber
        );

        $report = $reporter->report(...$slowTests);

        $expected = <<<'TXT'
Detected 5 tests that took longer than 100 ms.

12,345 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::bar
 7,890 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::foo with data set #123
 3,456 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::qux
 1,234 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::quz

There is one additional slow test that is not listed here.
TXT;

        self::assertSame($expected, $report);
    }

    public function testReportReturnsReportWhenTheNumberOfSlowTestsIsGreaterThanTheMaximumCountPlusOne(): void
    {
        $slowTests = [
            SlowTest::fromTestAndDuration(
                new Event\Code\Test(
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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
                    Fixture\ExampleTest::class,
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

        $reporter = new Reporter(
            $durationFormatter,
            $maximumDuration,
            $maximumNumber
        );

        $report = $reporter->report(...$slowTests);

        $expected = <<<'TXT'
Detected 5 tests that took longer than 100 ms.

12,345 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::bar
 7,890 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::foo with data set #123
 3,456 ms: Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\ExampleTest::qux

There are 2 additional slow tests that are not listed here.
TXT;

        self::assertSame($expected, $report);
    }
}
