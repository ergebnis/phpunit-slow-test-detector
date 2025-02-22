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
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestDescription;
use Ergebnis\PHPUnit\SlowTestDetector\TestDuration;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\DefaultReporter
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Count
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Formatter\DefaultDurationFormatter
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestDescription
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestDuration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier
 */
final class DefaultReporterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testReportReturnsEmptyStringWhenThereAreNoSlowTests()
    {
        $faker = self::faker();

        $reporter = new Reporter\DefaultReporter(
            new Formatter\DefaultDurationFormatter(),
            MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(0))),
            Count::fromInt($faker->numberBetween(1))
        );

        $report = $reporter->report();

        self::assertSame('', $report);
    }

    /**
     * @dataProvider provideExpectedReportMaximumDurationMaximumCountAndSlowTests
     *
     * @param list<SlowTest> $slowTests
     */
    public function testReportReturnsReportWhenThereAreFewerSlowTestsThanMaximumCount(
        string $expectedReport,
        MaximumDuration $maximumDuration,
        Count $maximumCount,
        array $slowTests
    ) {
        $reporter = new Reporter\DefaultReporter(
            new Formatter\DefaultDurationFormatter(),
            $maximumDuration,
            $maximumCount
        );

        $report = $reporter->report(...$slowTests);

        self::assertSame($expectedReport, $report);
    }

    /**
     * @return \Generator<string, array{0: string, 1: MaximumDuration, 2: Count, 3: list<SlowTest>}>
     */
    public static function provideExpectedReportMaximumDurationMaximumCountAndSlowTests(): iterable
    {
        $values = [
            'header-singular' => [
                <<<'TXT'
Detected 1 test where the duration exceeded the maximum duration.

1. 0.300 (0.100) FooTest::test
TXT
                ,
                MaximumDuration::fromDuration(Duration::fromMilliseconds(500)),
                Count::fromInt(1),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(300)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                ],
            ],
            'header-plural' => [
                <<<'TXT'
Detected 2 tests where the duration exceeded the maximum duration.

1. 0.300 (0.100) FooTest::test
2. 0.275 (0.100) BarTest::test
TXT
                ,
                MaximumDuration::fromDuration(Duration::fromMilliseconds(500)),
                Count::fromInt(2),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(300)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        TestDescription::fromString('BarTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(275)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                ],
            ],
            'list-sorted' => [
                <<<'TXT'
Detected 3 tests where the duration exceeded the maximum duration.

1. 0.300 (0.100) FooTest::test
2. 0.275 (0.100) BarTest::test
3. 0.250 (0.100) BazTest::test
TXT
                ,
                MaximumDuration::fromDuration(Duration::fromMilliseconds(500)),
                Count::fromInt(3),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(300)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        TestDescription::fromString('BarTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(275)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        TestDescription::fromString('BazTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(250)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                ],
            ],
            'list-unsorted' => [
                <<<'TXT'
Detected 3 tests where the duration exceeded the maximum duration.

1. 0.300 (0.100) FooTest::test
2. 0.275 (0.100) BarTest::test
3. 0.250 (0.100) BazTest::test
TXT
                ,
                MaximumDuration::fromDuration(Duration::fromMilliseconds(500)),
                Count::fromInt(3),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        TestDescription::fromString('BazTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(250)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        TestDescription::fromString('BarTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(275)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(300)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                ],
            ],
            'list-different-maximum-duration' => [
                <<<'TXT'
Detected 10 tests where the duration exceeded the maximum duration.

 1. 20:50.000 (16:40.000) FooTest::test
 2.  9:35.000 ( 8:20.000) BarTest::test
 3.     0.250 (    0.100) BazTest::test
 4.     0.200 (    0.100) QuxTest::test
 5.     0.160 (    0.100) QuuxTest::test
 6.     0.150 (    0.100) CorgeTest::test
 7.     0.140 (    0.100) GraultTest::test
 8.     0.130 (    0.100) GarplyTest::test
 9.     0.120 (    0.100) WaldoTest::test
10.     0.110 (    0.100) FredTest::test
TXT
                ,
                MaximumDuration::fromDuration(Duration::fromMilliseconds(500)),
                Count::fromInt(10),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(1250000)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(1000000))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        TestDescription::fromString('BarTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(575000)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(500000))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        TestDescription::fromString('BazTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(250)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('QuxTest::test'),
                        TestDescription::fromString('QuxTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(200)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('QuuxTest::test'),
                        TestDescription::fromString('QuuxTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(160)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('CorgeTest::test'),
                        TestDescription::fromString('CorgeTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(150)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('GraultTest::test'),
                        TestDescription::fromString('GraultTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(140)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('GarplyTest::test'),
                        TestDescription::fromString('GarplyTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(130)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('WaldoTest::test'),
                        TestDescription::fromString('WaldoTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(120)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('FredTest::test'),
                        TestDescription::fromString('FredTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(110)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                ],
            ],
            'footer-singular' => [
                <<<'TXT'
Detected 2 tests where the duration exceeded the maximum duration.

1. 0.300 (0.100) FooTest::test

There is 1 additional slow test that is not listed here.
TXT
                ,
                MaximumDuration::fromDuration(Duration::fromMilliseconds(500)),
                Count::fromInt(1),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(300)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        TestDescription::fromString('BarTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(275)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                ],
            ],
            'footer-plural' => [
                <<<'TXT'
Detected 3 tests where the duration exceeded the maximum duration.

1. 0.300 (0.100) FooTest::test

There are 2 additional slow tests that are not listed here.
TXT
                ,
                MaximumDuration::fromDuration(Duration::fromMilliseconds(500)),
                Count::fromInt(1),
                [
                    SlowTest::create(
                        TestIdentifier::fromString('FooTest::test'),
                        TestDescription::fromString('FooTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(300)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BarTest::test'),
                        TestDescription::fromString('BarTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(275)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                    SlowTest::create(
                        TestIdentifier::fromString('BazTest::test'),
                        TestDescription::fromString('BazTest::test'),
                        TestDuration::fromDuration(Duration::fromMilliseconds(250)),
                        MaximumDuration::fromDuration(Duration::fromMilliseconds(100))
                    ),
                ],
            ],
        ];

        foreach ($values as $key => list($expected, $maximumDuration, $maximumCount, $slowTests)) {
            yield $key => [
                $expected,
                $maximumDuration,
                $maximumCount,
                $slowTests,
            ];
        }
    }
}
