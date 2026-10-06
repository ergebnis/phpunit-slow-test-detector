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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Reporter\Compact;

use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\Renderer;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestList;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestDescription;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Compact\CompactReporter
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Count
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumCount
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\CompactRenderer
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Printer
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Sanitizer
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Reporter\DurationFormatter
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Unit
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTestList
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestDescription
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier
 */
final class CompactReporterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testReportPrintsNothingWhenSlowTestListIsEmpty(): void
    {
        $slowTestList = SlowTestList::create();

        $output = self::output();

        $reporter = new Reporter\Compact\CompactReporter(
            new Renderer\Printer($output),
            new Renderer\CompactRenderer(new Renderer\Sanitizer()),
            new Reporter\DurationFormatter(),
            MaximumCount::fromCount(Count::fromInt(self::faker()->numberBetween(1))),
        );

        $reporter->report($slowTestList);

        self::assertOutputIsIdenticalTo('', $output);
    }

    /**
     * @dataProvider provideExpectedReportAndSlowTestList
     */
    public function testReportPrintsReportWhenSlowTestListHasFewerSlowTestsThanMaximumCount(
        string $expectedReport,
        SlowTestList $slowTestList
    ): void {
        $output = self::output();

        $reporter = new Reporter\Compact\CompactReporter(
            new Renderer\Printer($output),
            new Renderer\CompactRenderer(new Renderer\Sanitizer()),
            new Reporter\DurationFormatter(),
            MaximumCount::unlimited(),
        );

        $reporter->report($slowTestList);

        self::assertOutputIsIdenticalTo($expectedReport, $output);
    }

    /**
     * @return \Generator<string, array{0: string, 1: SlowTestList}>
     */
    public static function provideExpectedReportAndSlowTestList(): iterable
    {
        $values = [
            'global-maximum-duration' => [
                self::lines([
                    '',
                    '=== ergebnis/phpunit-slow-test-detector ===',
                    '',
                    '--- SLOW: FooTest::test',
                    '0.300 seconds (maximum 0.100 seconds)',
                    '',
                    'SLOW (1 test)',
                ]),
                SlowTestList::create(self::slowTest(
                    'FooTest::test',
                    300,
                    100,
                )),
            ],
            'custom-maximum-duration' => [
                self::lines([
                    '',
                    '=== ergebnis/phpunit-slow-test-detector ===',
                    '',
                    '--- SLOW: FooTest::test',
                    '0.300 seconds (maximum 0.200 seconds)',
                    '',
                    'SLOW (1 test)',
                ]),
                SlowTestList::create(self::slowTest(
                    'FooTest::test',
                    300,
                    200,
                )),
            ],
            'sorted-by-duration-descending' => [
                self::lines([
                    '',
                    '=== ergebnis/phpunit-slow-test-detector ===',
                    '',
                    '--- SLOW: BarTest::test',
                    '0.500 seconds (maximum 0.100 seconds)',
                    '',
                    '--- SLOW: BazTest::test',
                    '0.400 seconds (maximum 0.200 seconds)',
                    '',
                    '--- SLOW: FooTest::test',
                    '0.300 seconds (maximum 0.100 seconds)',
                    '',
                    'SLOW (3 tests)',
                ]),
                SlowTestList::create(
                    self::slowTest(
                        'FooTest::test',
                        300,
                        100,
                    ),
                    self::slowTest(
                        'BarTest::test',
                        500,
                        100,
                    ),
                    self::slowTest(
                        'BazTest::test',
                        400,
                        200,
                    ),
                ),
            ],
            'duration-longer-than-one-minute' => [
                self::lines([
                    '',
                    '=== ergebnis/phpunit-slow-test-detector ===',
                    '',
                    '--- SLOW: FooTest::test',
                    '75.300 seconds (maximum 0.500 seconds)',
                    '',
                    'SLOW (1 test)',
                ]),
                SlowTestList::create(self::slowTest(
                    'FooTest::test',
                    75300,
                    500,
                )),
            ],
            'duration-longer-than-one-hour' => [
                self::lines([
                    '',
                    '=== ergebnis/phpunit-slow-test-detector ===',
                    '',
                    '--- SLOW: FooTest::test',
                    '3725.001 seconds (maximum 60.000 seconds)',
                    '',
                    'SLOW (1 test)',
                ]),
                SlowTestList::create(self::slowTest(
                    'FooTest::test',
                    3725001,
                    60000,
                )),
            ],
            'test-description-with-data-set' => [
                self::lines([
                    '',
                    '=== ergebnis/phpunit-slow-test-detector ===',
                    '',
                    '--- SLOW: FooTest::test with data set #1 (1000)',
                    '1.000 seconds (maximum 0.500 seconds)',
                    '',
                    'SLOW (1 test)',
                ]),
                SlowTestList::create(self::slowTest(
                    'FooTest::test with data set #1 (1000)',
                    1000,
                    500,
                )),
            ],
        ];

        foreach ($values as $key => [$expectedReport, $slowTestList]) {
            yield $key => [
                $expectedReport,
                $slowTestList,
            ];
        }
    }

    public function testReportPrintsReportWhenSlowTestListHasOneSlowTestMoreThanMaximumCount(): void
    {
        $slowTestList = SlowTestList::create(
            self::slowTest(
                'FooTest::test',
                300,
                100,
            ),
            self::slowTest(
                'BarTest::test',
                500,
                100,
            ),
        );

        $output = self::output();

        $reporter = new Reporter\Compact\CompactReporter(
            new Renderer\Printer($output),
            new Renderer\CompactRenderer(new Renderer\Sanitizer()),
            new Reporter\DurationFormatter(),
            MaximumCount::fromCount(Count::fromInt(1)),
        );

        $reporter->report($slowTestList);

        $expectedReport = self::lines([
            '',
            '=== ergebnis/phpunit-slow-test-detector ===',
            '',
            '--- SLOW: BarTest::test',
            '0.500 seconds (maximum 0.100 seconds)',
            '',
            'SLOW (2 tests, 1 not listed)',
        ]);

        self::assertOutputIsIdenticalTo($expectedReport, $output);
    }

    public function testReportPrintsReportWhenSlowTestListHasSeveralSlowTestsMoreThanMaximumCount(): void
    {
        $slowTestList = SlowTestList::create(
            self::slowTest(
                'FooTest::test',
                300,
                100,
            ),
            self::slowTest(
                'BarTest::test',
                500,
                100,
            ),
            self::slowTest(
                'BazTest::test',
                400,
                100,
            ),
        );

        $output = self::output();

        $reporter = new Reporter\Compact\CompactReporter(
            new Renderer\Printer($output),
            new Renderer\CompactRenderer(new Renderer\Sanitizer()),
            new Reporter\DurationFormatter(),
            MaximumCount::fromCount(Count::fromInt(1)),
        );

        $reporter->report($slowTestList);

        $expectedReport = self::lines([
            '',
            '=== ergebnis/phpunit-slow-test-detector ===',
            '',
            '--- SLOW: BarTest::test',
            '0.500 seconds (maximum 0.100 seconds)',
            '',
            'SLOW (3 tests, 2 not listed)',
        ]);

        self::assertOutputIsIdenticalTo($expectedReport, $output);
    }

    public function testReportEscapesControlCharactersAndLineBreaksInTestDescription(): void
    {
        $slowTestList = SlowTestList::create(self::slowTest(
            "FooTest::test with data set \"foo\r\nbar\e[31m\"",
            300,
            100,
        ));

        $output = self::output();

        $reporter = new Reporter\Compact\CompactReporter(
            new Renderer\Printer($output),
            new Renderer\CompactRenderer(new Renderer\Sanitizer()),
            new Reporter\DurationFormatter(),
            MaximumCount::unlimited(),
        );

        $reporter->report($slowTestList);

        $expectedReport = self::lines([
            '',
            '=== ergebnis/phpunit-slow-test-detector ===',
            '',
            '--- SLOW: FooTest::test with data set "foo\u{000D}\u{000A}bar\u{001B}[31m"',
            '0.300 seconds (maximum 0.100 seconds)',
            '',
            'SLOW (1 test)',
        ]);

        self::assertOutputIsIdenticalTo($expectedReport, $output);
    }

    /**
     * @param list<string> $lines
     */
    private static function lines(array $lines): string
    {
        return \implode(
            \PHP_EOL,
            $lines,
        ) . \PHP_EOL;
    }

    private static function slowTest(
        string $testDescription,
        int $durationInMilliseconds,
        int $maximumDurationInMilliseconds
    ): SlowTest {
        return SlowTest::create(
            TestIdentifier::fromString($testDescription),
            TestDescription::fromString($testDescription),
            Duration::fromMilliseconds($durationInMilliseconds),
            MaximumDuration::fromDuration(Duration::fromMilliseconds($maximumDurationInMilliseconds)),
        );
    }
}
