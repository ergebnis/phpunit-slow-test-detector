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
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\ConsoleReporter
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
final class ConsoleReporterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testReportReturnsEmptyStringWhenSlowTestListIsEmpty()
    {
        $faker = self::faker();

        $slowTestList = SlowTestList::create();

        $reporter = new Reporter\ConsoleReporter(
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
    public function testReportReturnsReportWhenSlowTestListHasFewerSlowTestsThanMaximumCount(
        string $expectedReport,
        MaximumDuration $maximumDuration,
        MaximumCount $maximumCount,
        SlowTestList $slowTestList
    ) {
        $reporter = new Reporter\ConsoleReporter(
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
            return \implode(
                "\n",
                $lines
            );
        };

        $values = [
            'header-singular-global-only' => [
                $print([
                    '',
                    '',
                    'Detected 1 test where the duration exceeded the global maximum duration (0.100).',
                    '',
                    '# Duration Test',
                    '------------------------',
                    '1    0.300 FooTest::test',
                    '------------------------',
                    '     0.000',
                    '      └─── seconds',
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
            'header-singular-custom' => [
                $print([
                    '',
                    '',
                    'Detected 1 test where the duration exceeded a custom or the global maximum duration (0.100).',
                    '',
                    '# Duration          Test',
                    '  Actual   Maximum',
                    '---------------------------------',
                    '1    0.300    0.200 FooTest::test',
                    '---------------------------------',
                    '     0.000',
                    '      └─── seconds',
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
            'header-plural-global-only' => [
                $print([
                    '',
                    '',
                    'Detected 2 tests where the duration exceeded the global maximum duration (0.100).',
                    '',
                    '# Duration Test',
                    '------------------------',
                    '1    0.300 FooTest::test',
                    '2    0.275 BarTest::test',
                    '------------------------',
                    '     0.000',
                    '      └─── seconds',
                ]),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
                MaximumCount::fromCount(Count::fromInt(2)),
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
            'header-plural-custom' => [
                $print([
                    '',
                    '',
                    'Detected 2 tests where the duration exceeded a custom or the global maximum duration (0.100).',
                    '',
                    '# Duration          Test',
                    '  Actual   Maximum',
                    '---------------------------------',
                    '1    0.300    0.200 FooTest::test',
                    '2    0.275          BarTest::test',
                    '---------------------------------',
                    '     0.000',
                    '      └─── seconds',
                ]),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
                MaximumCount::fromCount(Count::fromInt(2)),
                SlowTestList::create(
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        Duration::fromMilliseconds(300),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(200))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        TestDescription::fromString('BarTest::test'),
                        Duration::fromMilliseconds(275),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    )
                ),
            ],
            'list-sorted' => [
                $print([
                    '',
                    '',
                    'Detected 3 tests where the duration exceeded the global maximum duration (0.100).',
                    '',
                    '# Duration Test',
                    '------------------------',
                    '1    0.300 FooTest::test',
                    '2    0.275 BarTest::test',
                    '3    0.250 BazTest::test',
                    '------------------------',
                    '     0.000',
                    '      └─── seconds',
                ]),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
                MaximumCount::fromCount(Count::fromInt(3)),
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
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        TestDescription::fromString('BazTest::test'),
                        Duration::fromMilliseconds(250),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    )
                ),
            ],
            'list-unsorted' => [
                $print([
                    '',
                    '',
                    'Detected 3 tests where the duration exceeded the global maximum duration (0.100).',
                    '',
                    '# Duration Test',
                    '------------------------',
                    '1    0.300 FooTest::test',
                    '2    0.275 BarTest::test',
                    '3    0.250 BazTest::test',
                    '------------------------',
                    '     0.000',
                    '      └─── seconds',
                ]),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
                MaximumCount::fromCount(Count::fromInt(3)),
                SlowTestList::create(
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        TestDescription::fromString('BazTest::test'),
                        Duration::fromMilliseconds(250),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
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
            'list-different-maximum-duration' => [
                $print([
                    '',
                    '',
                    'Detected 10 tests where the duration exceeded a custom or the global maximum duration (0:00.100).',
                    '',
                    ' # Duration            Test',
                    '   Actual    Maximum',
                    '---------------------------------------',
                    ' 1 20:50.000 16:40.000 FooTest::test',
                    ' 2  9:35.000  8:20.000 BarTest::test',
                    ' 3  0:00.250           BazTest::test',
                    ' 4  0:00.200           QuxTest::test',
                    ' 5  0:00.160           QuuxTest::test',
                    ' 6  0:00.150           CorgeTest::test',
                    ' 7  0:00.140           GraultTest::test',
                    ' 8  0:00.130           GarplyTest::test',
                    ' 9  0:00.120           WaldoTest::test',
                    '10  0:00.110           FredTest::test',
                    '---------------------------------------',
                    '    0:00.000',
                    '     │  └─── seconds',
                    '     └────── minutes',
                ]),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
                MaximumCount::fromCount(Count::fromInt(10)),
                SlowTestList::create(
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        Duration::fromMilliseconds(1250000),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(1000000))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        TestDescription::fromString('BarTest::test'),
                        Duration::fromMilliseconds(575000),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(500000))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        TestDescription::fromString('BazTest::test'),
                        Duration::fromMilliseconds(250),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('QuxTest::test'),
                        TestDescription::fromString('QuxTest::test'),
                        Duration::fromMilliseconds(200),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('QuuxTest::test'),
                        TestDescription::fromString('QuuxTest::test'),
                        Duration::fromMilliseconds(160),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('CorgeTest::test'),
                        TestDescription::fromString('CorgeTest::test'),
                        Duration::fromMilliseconds(150),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('GraultTest::test'),
                        TestDescription::fromString('GraultTest::test'),
                        Duration::fromMilliseconds(140),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('GarplyTest::test'),
                        TestDescription::fromString('GarplyTest::test'),
                        Duration::fromMilliseconds(130),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('WaldoTest::test'),
                        TestDescription::fromString('WaldoTest::test'),
                        Duration::fromMilliseconds(120),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('FredTest::test'),
                        TestDescription::fromString('FredTest::test'),
                        Duration::fromMilliseconds(110),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    )
                ),
            ],
            'list-different-maximum-duration-hours-global-only' => [
                $print([
                    '',
                    '',
                    'Detected 3 tests where the duration exceeded the global maximum duration (0:00:00.100).',
                    '',
                    '# Duration    Test',
                    '---------------------------',
                    '1 1:23:45.678 FooTest::test',
                    '2 0:09:35.000 BarTest::test',
                    '3 0:00:00.250 BazTest::test',
                    '---------------------------',
                    '  0:00:00.000',
                    '   │  │  └─── seconds',
                    '   │  └────── minutes',
                    '   └───────── hours',
                ]),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
                MaximumCount::fromCount(Count::fromInt(3)),
                SlowTestList::create(
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        Duration::fromMilliseconds(5025678),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        TestDescription::fromString('BarTest::test'),
                        Duration::fromMilliseconds(575000),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        TestDescription::fromString('BazTest::test'),
                        Duration::fromMilliseconds(250),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    )
                ),
            ],
            'list-different-maximum-duration-hours-custom' => [
                $print([
                    '',
                    '',
                    'Detected 3 tests where the duration exceeded a custom or the global maximum duration (0:00:00.100).',
                    '',
                    '# Duration                Test',
                    '  Actual      Maximum',
                    '---------------------------------------',
                    '1 1:23:45.678 1:00:00.000 FooTest::test',
                    '2 0:09:35.000 0:08:20.000 BarTest::test',
                    '3 0:00:00.250             BazTest::test',
                    '---------------------------------------',
                    '  0:00:00.000',
                    '   │  │  └─── seconds',
                    '   │  └────── minutes',
                    '   └───────── hours',
                ]),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
                MaximumCount::fromCount(Count::fromInt(3)),
                SlowTestList::create(
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        Duration::fromMilliseconds(5025678),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(3600000))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        TestDescription::fromString('BarTest::test'),
                        Duration::fromMilliseconds(575000),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(500000))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        TestDescription::fromString('BazTest::test'),
                        Duration::fromMilliseconds(250),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    )
                ),
            ],
            'footer-singular' => [
                $print([
                    '',
                    '',
                    'Detected 2 tests where the duration exceeded the global maximum duration (0.100).',
                    '',
                    '# Duration Test',
                    '------------------------',
                    '1    0.300 FooTest::test',
                    '------------------------',
                    '     0.000',
                    '      └─── seconds',
                    '',
                    'There is 1 additional slow test that is not listed here.',
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
            'footer-plural' => [
                $print([
                    '',
                    '',
                    'Detected 3 tests where the duration exceeded the global maximum duration (0.100).',
                    '',
                    '# Duration Test',
                    '------------------------',
                    '1    0.300 FooTest::test',
                    '------------------------',
                    '     0.000',
                    '      └─── seconds',
                    '',
                    'There are 2 additional slow tests that are not listed here.',
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
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        TestDescription::fromString('BazTest::test'),
                        Duration::fromMilliseconds(250),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    )
                ),
            ],
        ];

        foreach ($values as $key => list($expected, $maximumDuration, $maximumCount, $slowTestList)) {
            yield $key => [
                $expected,
                $maximumDuration,
                $maximumCount,
                $slowTestList,
            ];
        }
    }
}
