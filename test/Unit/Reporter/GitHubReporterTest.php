<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2026 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Reporter;

use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestList;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestDescription;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\GitHubReporter
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Count
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumCount
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Formatter\DefaultDurationFormatter
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Formatter\Unit
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTestList
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestDescription
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier
 */
final class GitHubReporterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testReportReturnsEmptyStringWhenSlowTestListIsEmpty()
    {
        $faker = self::faker();

        $slowTestList = SlowTestList::create();

        $reporter = new Reporter\GitHubReporter(
            new Reporter\Formatter\DefaultDurationFormatter(),
            MaximumDuration::default(),
            MaximumCount::fromCount(Count::fromInt($faker->numberBetween(1)))
        );

        $report = $reporter->report($slowTestList);

        self::assertSame('', $report);
    }

    /**
     * @dataProvider provideExpectedReportMaximumDurationMaximumCountAndSlowTestList
     */
    public function testReportReturnsReportWhenSlowTestListIsNotEmpty(
        string $expectedReport,
        MaximumDuration $maximumDuration,
        MaximumCount $maximumCount,
        SlowTestList $slowTestList
    ) {
        $reporter = new Reporter\GitHubReporter(
            new Reporter\Formatter\DefaultDurationFormatter(),
            $maximumDuration,
            $maximumCount
        );

        $report = $reporter->report($slowTestList);

        self::assertSame($expectedReport, $report);
    }

    /**
     * @return \Generator<string, array{0: string, 1: MaximumDuration, 2: MaximumCount, 3: SlowTestList}>
     */
    public static function provideExpectedReportMaximumDurationMaximumCountAndSlowTestList(): iterable
    {
        $print = static function (array $lines): string {
            return "\n" . \implode(
                "\n",
                $lines
            );
        };

        $values = [
            'single-slow-test' => [
                $print([
                    '::warning title=Slow Test::FooTest::test took 0.300, maximum allowed is 0.100',
                ]),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
                MaximumCount::fromCount(Count::fromInt(1)),
                SlowTestList::create(
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        Duration::fromMilliseconds(300),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    )
                ),
            ],
            'multiple-slow-tests-sorted-by-duration' => [
                $print([
                    '::warning title=Slow Test::FooTest::test took 0.300, maximum allowed is 0.100',
                    '::warning title=Slow Test::BarTest::test took 0.275, maximum allowed is 0.100',
                ]),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
                MaximumCount::fromCount(Count::fromInt(2)),
                SlowTestList::create(
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        TestDescription::fromString('BarTest::test'),
                        Duration::fromMilliseconds(275),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        Duration::fromMilliseconds(300),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    )
                ),
            ],
            'limited-by-maximum-count' => [
                $print([
                    '::warning title=Slow Test::FooTest::test took 0.300, maximum allowed is 0.100',
                ]),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
                MaximumCount::fromCount(Count::fromInt(1)),
                SlowTestList::create(
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        Duration::fromMilliseconds(300),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        TestDescription::fromString('BarTest::test'),
                        Duration::fromMilliseconds(275),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    )
                ),
            ],
            'with-custom-maximum-duration' => [
                $print([
                    '::warning title=Slow Test::FooTest::test took 0.300, maximum allowed is 0.200',
                ]),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
                MaximumCount::fromCount(Count::fromInt(1)),
                SlowTestList::create(
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        Duration::fromMilliseconds(300),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(200))
                    )
                ),
            ],
            'escapes-percent-sign' => [
                $print([
                    '::warning title=Slow Test::FooTest::test with data set "100%25 complete" took 0.300, maximum allowed is 0.100',
                ]),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
                MaximumCount::fromCount(Count::fromInt(1)),
                SlowTestList::create(
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test with data set "100% complete"'),
                        TestDescription::fromString('FooTest::test with data set "100% complete"'),
                        Duration::fromMilliseconds(300),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    )
                ),
            ],
        ];

        foreach ($values as $key => $value) {
            yield $key => $value;
        }
    }
}
