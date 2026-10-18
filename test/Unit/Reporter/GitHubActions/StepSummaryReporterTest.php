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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Reporter\GitHubActions;

use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\GitHubStepSummary;
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
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\GitHubActions\StepSummaryReporter
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Count
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\GitHubStepSummary
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumCount
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\Alignment
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\Column
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\InlineCode
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\Table
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\TableRenderer
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Printer
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Reporter\DurationFormatter
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Unit
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTestList
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestDescription
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier
 */
final class StepSummaryReporterTest extends Framework\TestCase
{
    use Test\Util\Helper;
    private string $file = '';

    protected function setUp(): void
    {
        $file = \tempnam(
            \sys_get_temp_dir(),
            'github-step-summary',
        );

        if (!\is_string($file)) {
            self::fail('Unable to create a temporary file.');
        }

        $this->file = $file;
    }

    protected function tearDown(): void
    {
        if (\is_file($this->file)) {
            \unlink($this->file);
        }
    }

    public function testReportWritesNothingWhenSlowTestListIsEmpty(): void
    {
        $reporter = self::reporter(
            GitHubStepSummary::fromString($this->file),
            MaximumCount::fromCount(Count::fromInt(self::faker()->numberBetween(1))),
        );

        $reporter->report(SlowTestList::create());

        self::assertSame('', \file_get_contents($this->file));
    }

    public function testReportWritesNothingAndDoesNotWarnWhenFileCannotBeOpened(): void
    {
        $faker = self::faker();

        $file = \sprintf(
            '%s/%s/%s',
            \sys_get_temp_dir(),
            $faker->slug(),
            $faker->slug(),
        );

        $reporter = self::reporter(
            GitHubStepSummary::fromString($file),
            MaximumCount::fromCount(Count::fromInt(3)),
        );

        $errors = [];

        \set_error_handler(static function (int $level, string $message) use (&$errors): bool {
            $errors[] = $message;

            return true;
        });

        try {
            $reporter->report(SlowTestList::create(
                self::slowTest(
                    'FooTest::test',
                    900,
                    500,
                ),
                self::slowTest(
                    'BarTest::test',
                    800,
                    500,
                ),
            ));
        } finally {
            \restore_error_handler();
        }

        self::assertSame([], $errors);
    }

    public function testReportAppendsSummaryWhenSlowTestListHasFewerSlowTestsThanMaximumCount(): void
    {
        \file_put_contents(
            $this->file,
            "previous step\n",
        );

        $reporter = self::reporter(
            GitHubStepSummary::fromString($this->file),
            MaximumCount::fromCount(Count::fromInt(3)),
        );

        $reporter->report(SlowTestList::create(
            self::slowTest(
                'FooTest::test',
                900,
                500,
            ),
            self::slowTest(
                'BarTest::test with data set #1 (1, 2)',
                61234,
                500,
            ),
        ));

        $expected = \implode("\n", [
            'previous step',
            '',
            '## Slow tests',
            '',
            'Detected 2 tests where the duration exceeded the global maximum duration (0.500 seconds).',
            '',
            '| # | Duration | Maximum | Test |',
            '|--:|--:|--:|:--|',
            '| 1 | 61.234 | 0.500 | `BarTest::test with data set #1 (1, 2)` |',
            '| 2 | 0.900 | 0.500 | `FooTest::test` |',
            '',
        ]);

        self::assertSame($expected, \file_get_contents($this->file));
    }

    public function testReportAppendsSummaryWithAdditionalSlowTestsSentenceWhenSlowTestListHasMoreSlowTestsThanMaximumCount(): void
    {
        $reporter = self::reporter(
            GitHubStepSummary::fromString($this->file),
            MaximumCount::fromCount(Count::fromInt(1)),
        );

        $reporter->report(SlowTestList::create(
            self::slowTest(
                'FooTest::test',
                900,
                500,
            ),
            self::slowTest(
                'BarTest::test',
                800,
                500,
            ),
            self::slowTest(
                'BazTest::test',
                700,
                500,
            ),
        ));

        $expected = \implode("\n", [
            '',
            '## Slow tests',
            '',
            'Detected 3 tests where the duration exceeded the global maximum duration (0.500 seconds).',
            '',
            '| # | Duration | Maximum | Test |',
            '|--:|--:|--:|:--|',
            '| 1 | 0.900 | 0.500 | `FooTest::test` |',
            '',
            'There are 2 additional slow tests that are not listed here.',
            '',
        ]);

        self::assertSame($expected, \file_get_contents($this->file));
    }

    public function testReportAppendsSummaryWithSingularAdditionalSlowTestSentenceWhenSlowTestListHasOneMoreSlowTestThanMaximumCount(): void
    {
        $reporter = self::reporter(
            GitHubStepSummary::fromString($this->file),
            MaximumCount::fromCount(Count::fromInt(1)),
        );

        $reporter->report(SlowTestList::create(
            self::slowTest(
                'FooTest::test',
                900,
                500,
            ),
            self::slowTest(
                'BarTest::test',
                800,
                500,
            ),
        ));

        $expected = \implode("\n", [
            '',
            '## Slow tests',
            '',
            'Detected 2 tests where the duration exceeded the global maximum duration (0.500 seconds).',
            '',
            '| # | Duration | Maximum | Test |',
            '|--:|--:|--:|:--|',
            '| 1 | 0.900 | 0.500 | `FooTest::test` |',
            '',
            'There is 1 additional slow test that is not listed here.',
            '',
        ]);

        self::assertSame($expected, \file_get_contents($this->file));
    }

    public function testReportAppendsSummaryWithSingularSentenceWhenSlowTestListHasOneSlowTest(): void
    {
        $reporter = self::reporter(
            GitHubStepSummary::fromString($this->file),
            MaximumCount::fromCount(Count::fromInt(3)),
        );

        $reporter->report(SlowTestList::create(self::slowTest(
            'FooTest::test',
            900,
            500,
        )));

        $expected = \implode("\n", [
            '',
            '## Slow tests',
            '',
            'Detected 1 test where the duration exceeded the global maximum duration (0.500 seconds).',
            '',
            '| # | Duration | Maximum | Test |',
            '|--:|--:|--:|:--|',
            '| 1 | 0.900 | 0.500 | `FooTest::test` |',
            '',
        ]);

        self::assertSame($expected, \file_get_contents($this->file));
    }

    public function testReportAppendsSummaryWithCustomMaximumDurationSentenceWhenSlowTestHasCustomMaximumDuration(): void
    {
        $reporter = self::reporter(
            GitHubStepSummary::fromString($this->file),
            MaximumCount::fromCount(Count::fromInt(3)),
        );

        $reporter->report(SlowTestList::create(self::slowTest(
            'FooTest::test',
            1500,
            1000,
        )));

        $expected = \implode("\n", [
            '',
            '## Slow tests',
            '',
            'Detected 1 test where the duration exceeded a custom or the global maximum duration (0.500 seconds).',
            '',
            '| # | Duration | Maximum | Test |',
            '|--:|--:|--:|:--|',
            '| 1 | 1.500 | 1.000 | `FooTest::test` |',
            '',
        ]);

        self::assertSame($expected, \file_get_contents($this->file));
    }

    public function testReportEscapesTestDescription(): void
    {
        $reporter = self::reporter(
            GitHubStepSummary::fromString($this->file),
            MaximumCount::fromCount(Count::fromInt(3)),
        );

        $reporter->report(SlowTestList::create(self::slowTest(
            "FooTest::test with data set \"a|b`c\nd\"",
            900,
            500,
        )));

        $expected = \implode("\n", [
            '',
            '## Slow tests',
            '',
            'Detected 1 test where the duration exceeded the global maximum duration (0.500 seconds).',
            '',
            '| # | Duration | Maximum | Test |',
            '|--:|--:|--:|:--|',
            '| 1 | 0.900 | 0.500 | ``FooTest::test with data set "a\|b`c d"`` |',
            '',
        ]);

        self::assertSame($expected, \file_get_contents($this->file));
    }

    private static function reporter(
        GitHubStepSummary $gitHubStepSummary,
        MaximumCount $maximumCount
    ): Reporter\GitHubActions\StepSummaryReporter {
        return new Reporter\GitHubActions\StepSummaryReporter(
            $gitHubStepSummary,
            new Renderer\Markdown\TableRenderer(),
            new Reporter\DurationFormatter(),
            MaximumDuration::fromDuration(Duration::fromMilliseconds(500)),
            $maximumCount,
        );
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
            null,
        );
    }
}
