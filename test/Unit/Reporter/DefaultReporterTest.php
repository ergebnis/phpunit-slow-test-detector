<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Reporter;

use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Formatter;
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
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\DefaultReporter
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Count
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Formatter\DefaultDurationFormatter
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumCount
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTestList
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestDescription
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier
 */
final class DefaultReporterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testReportReturnsEmptyStringWhenSlowTestListIsEmpty()
    {
        $faker = self::faker();

        $slowTestList = SlowTestList::create();

        $reporter = new Reporter\DefaultReporter(
            new Formatter\DefaultDurationFormatter(),
            MaximumCount::fromCount(Count::fromInt($faker->numberBetween(1)))
        );

        $report = $reporter->report($slowTestList);

        self::assertSame('', $report);
    }

    /**
     * @dataProvider provideExpectedReportMaximumCountAndSlowTestList
     */
    public function testReportReturnsReportWhenSlowTestListHasFewerSlowTestsThanMaximumCount(
        string $expectedReport,
        MaximumCount $maximumCount,
        SlowTestList $slowTestList
    ) {
        $reporter = new Reporter\DefaultReporter(
            new Formatter\DefaultDurationFormatter(),
            $maximumCount
        );

        $report = $reporter->report($slowTestList);

        self::assertSame($expectedReport, $report);
    }

    /**
     * @return \Generator<string, array{0: string, 1: MaximumCount, 2: SlowTestList}>
     */
    public static function provideExpectedReportMaximumCountAndSlowTestList(): iterable
    {
        $values = [
            'header-singular' => [
                <<<'TXT'
Detected 1 test where the duration exceeded the maximum duration.

1. 00:00.300 (00:00.100) FooTest::test
TXT
                ,
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
            'header-plural' => [
                <<<'TXT'
Detected 2 tests where the duration exceeded the maximum duration.

1. 00:00.300 (00:00.100) FooTest::test
2. 00:00.275 (00:00.100) BarTest::test
TXT
                ,
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
            'list-sorted' => [
                <<<'TXT'
Detected 3 tests where the duration exceeded the maximum duration.

1. 00:00.300 (00:00.100) FooTest::test
2. 00:00.275 (00:00.100) BarTest::test
3. 00:00.250 (00:00.100) BazTest::test
TXT
                ,
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
                <<<'TXT'
Detected 3 tests where the duration exceeded the maximum duration.

1. 00:00.300 (00:00.100) FooTest::test
2. 00:00.275 (00:00.100) BarTest::test
3. 00:00.250 (00:00.100) BazTest::test
TXT
                ,
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
                <<<'TXT'
Detected 10 tests where the duration exceeded the maximum duration.

 1. 20:50.000 (16:40.000) FooTest::test
 2. 09:35.000 (08:20.000) BarTest::test
 3. 00:00.250 (00:00.100) BazTest::test
 4. 00:00.200 (00:00.100) QuxTest::test
 5. 00:00.160 (00:00.100) QuuxTest::test
 6. 00:00.150 (00:00.100) CorgeTest::test
 7. 00:00.140 (00:00.100) GraultTest::test
 8. 00:00.130 (00:00.100) GarplyTest::test
 9. 00:00.120 (00:00.100) WaldoTest::test
10. 00:00.110 (00:00.100) FredTest::test
TXT
                ,
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
            'footer-singular' => [
                <<<'TXT'
Detected 2 tests where the duration exceeded the maximum duration.

1. 00:00.300 (00:00.100) FooTest::test

There is 1 additional slow test that is not listed here.
TXT
                ,
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
                <<<'TXT'
Detected 3 tests where the duration exceeded the maximum duration.

1. 00:00.300 (00:00.100) FooTest::test

There are 2 additional slow tests that are not listed here.
TXT
                ,
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

        foreach ($values as $key => list($expected, $maximumCount, $slowTestList)) {
            yield $key => [
                $expected,
                $maximumCount,
                $slowTestList,
            ];
        }
    }
}
