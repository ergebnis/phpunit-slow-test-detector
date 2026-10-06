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
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\GitHubActions\AnnotationReporter
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Count
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumCount
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\GitHubActions\WarningMessage
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\GitHubActions\WorkflowCommandRenderer
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Printer
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Reporter\DurationFormatter
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Unit
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTestList
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestDescription
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier
 */
final class AnnotationReporterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testReportPrintsNothingWhenSlowTestListIsEmpty(): void
    {
        $output = self::output();

        $reporter = new Reporter\GitHubActions\AnnotationReporter(
            new Renderer\Printer($output),
            new Renderer\GitHubActions\WorkflowCommandRenderer(),
            new Reporter\DurationFormatter(),
            MaximumCount::fromCount(Count::fromInt(self::faker()->numberBetween(1))),
        );

        $reporter->report(SlowTestList::create());

        self::assertOutputIsIdenticalTo('', $output);
    }

    public function testReportPrintsWarningsForSlowTestsSortedByDurationDescendingWhenSlowTestListHasFewerSlowTestsThanMaximumCount(): void
    {
        $output = self::output();

        $reporter = new Reporter\GitHubActions\AnnotationReporter(
            new Renderer\Printer($output),
            new Renderer\GitHubActions\WorkflowCommandRenderer(),
            new Reporter\DurationFormatter(),
            MaximumCount::fromCount(Count::fromInt(3)),
        );

        $reporter->report(SlowTestList::create(
            SlowTest::create(
                TestIdentifier::fromString('FooTest::test'),
                TestDescription::fromString('FooTest::test'),
                Duration::fromMilliseconds(300),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
            ),
            SlowTest::create(
                TestIdentifier::fromString('BarTest::test'),
                TestDescription::fromString('BarTest::test with data set #1 (1, 2)'),
                Duration::fromMilliseconds(61234),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(500)),
            ),
        ));

        $expected = \implode("\n", [
            '',
            '::warning title=Slow Test::BarTest::test with data set #1 (1, 2) took 61.234 seconds, maximum is 0.500 seconds',
            '::warning title=Slow Test::FooTest::test took 0.300 seconds, maximum is 0.100 seconds',
            '',
        ]);

        self::assertOutputIsIdenticalTo($expected, $output);
    }

    public function testReportPrintsWarningsForSlowTestsSortedByDurationDescendingAndLimitedToMaximumCountWhenSlowTestListHasMoreSlowTestsThanMaximumCount(): void
    {
        $output = self::output();

        $reporter = new Reporter\GitHubActions\AnnotationReporter(
            new Renderer\Printer($output),
            new Renderer\GitHubActions\WorkflowCommandRenderer(),
            new Reporter\DurationFormatter(),
            MaximumCount::fromCount(Count::fromInt(2)),
        );

        $reporter->report(SlowTestList::create(
            SlowTest::create(
                TestIdentifier::fromString('FooTest::test'),
                TestDescription::fromString('FooTest::test'),
                Duration::fromMilliseconds(300),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(100)),
            ),
            SlowTest::create(
                TestIdentifier::fromString('BarTest::test'),
                TestDescription::fromString('BarTest::test'),
                Duration::fromMilliseconds(3723456),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(500)),
            ),
            SlowTest::create(
                TestIdentifier::fromString('BazTest::test'),
                TestDescription::fromString('BazTest::test'),
                Duration::fromMilliseconds(700),
                MaximumDuration::fromDuration(Duration::fromMilliseconds(500)),
            ),
        ));

        $expected = \implode("\n", [
            '',
            '::warning title=Slow Test::BarTest::test took 3723.456 seconds, maximum is 0.500 seconds',
            '::warning title=Slow Test::BazTest::test took 0.700 seconds, maximum is 0.500 seconds',
            '',
        ]);

        self::assertOutputIsIdenticalTo($expected, $output);
    }
}
