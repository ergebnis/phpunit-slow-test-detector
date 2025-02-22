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

namespace Ergebnis\PHPUnit\SlowTestDetector\Reporter;

use Ergebnis\PHPUnit\SlowTestDetector\Comparator;
use Ergebnis\PHPUnit\SlowTestDetector\Formatter;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;

/**
 * @internal
 */
final class DefaultReporter implements Reporter
{
    /**
     * @var Formatter\DurationFormatter
     */
    private $durationFormatter;

    /**
     * @var MaximumDuration
     */
    private $maximumDuration;

    /**
     * @var MaximumCount
     */
    private $maximumCount;

    /**
     * @var Comparator\DurationComparator
     */
    private $durationComparator;

    public function __construct(
        Formatter\DurationFormatter $durationFormatter,
        MaximumDuration $maximumDuration,
        MaximumCount $maximumCount
    ) {
        $this->durationFormatter = $durationFormatter;
        $this->maximumDuration = $maximumDuration;
        $this->maximumCount = $maximumCount;
        $this->durationComparator = new Comparator\DurationComparator();
    }

    public function report(SlowTest ...$slowTests): string
    {
        if ([] === $slowTests) {
            return '';
        }

        $header = $this->header(...$slowTests);
        $list = $this->list(...$slowTests);
        $footer = $this->footer(...$slowTests);

        if ('' === $footer) {
            return <<<TXT
{$header}
{$list}
TXT;
        }

        return <<<TXT
{$header}
{$list}
{$footer}
TXT;
    }

    private function header(SlowTest ...$slowTests): string
    {
        $count = \count($slowTests);

        if (1 === $count) {
            return <<<TXT
Detected {$count} test where the duration exceeded the maximum duration.

TXT;
        }

        return <<<TXT
Detected {$count} tests where the duration exceeded the maximum duration.

TXT;
    }

    private function list(SlowTest ...$slowTests): string
    {
        $durationComparator = $this->durationComparator;

        \usort($slowTests, static function (SlowTest $one, SlowTest $two) use ($durationComparator): int {
            return $durationComparator->compare(
                $two->testDuration()->toDuration(),
                $one->testDuration()->toDuration()
            );
        });

        $slowTestsToReport = \array_slice(
            $slowTests,
            0,
            $this->maximumCount->toCount()->toInt()
        );

        /** @var SlowTest $slowTestWithLongestTestDuration */
        $slowTestWithLongestTestDuration = \reset($slowTestsToReport);

        $longestMaximumDuration = \array_reduce(
            $slowTestsToReport,
            static function (MaximumDuration $maximumDuration, SlowTest $slowTest): MaximumDuration {
                if ($maximumDuration->toDuration()->isLessThan($slowTest->maximumDuration()->toDuration())) {
                    return $slowTest->maximumDuration();
                }

                return $maximumDuration;
            },
            $this->maximumDuration
        );

        $durationFormatter = $this->durationFormatter;

        $numberWidth = \strlen((string) \count($slowTestsToReport));
        $testDurationWidth = \strlen($durationFormatter->format($slowTestWithLongestTestDuration->testDuration()->toDuration()));
        $maximumDurationWidth = \strlen($durationFormatter->format($longestMaximumDuration->toDuration()));

        $items = \array_map(static function (int $number, SlowTest $slowTest) use ($numberWidth, $durationFormatter, $testDurationWidth, $maximumDurationWidth): string {
            $formattedNumber = \str_pad(
                (string) $number,
                $numberWidth,
                ' ',
                \STR_PAD_LEFT
            );

            $formattedDuration = \str_pad(
                $durationFormatter->format($slowTest->testDuration()->toDuration()),
                $testDurationWidth,
                ' ',
                \STR_PAD_LEFT
            );

            $formattedMaximumDuration = \sprintf(
                '(%s)',
                \str_pad(
                    $durationFormatter->format($slowTest->maximumDuration()->toDuration()),
                    $maximumDurationWidth,
                    ' ',
                    \STR_PAD_LEFT
                )
            );

            $testDescription = $slowTest->testDescription()->toString();

            return <<<TXT
{$formattedNumber}. {$formattedDuration} {$formattedMaximumDuration} {$testDescription}
TXT;
        }, \range(1, \count($slowTestsToReport)), $slowTestsToReport);

        return \implode(
            "\n",
            $items
        );
    }

    private function footer(SlowTest ...$slowTests): string
    {
        $additionalSlowTestCount = \max(
            \count($slowTests) - $this->maximumCount->toCount()->toInt(),
            0
        );

        if (0 === $additionalSlowTestCount) {
            return '';
        }

        if (1 === $additionalSlowTestCount) {
            return <<<'TXT'

There is 1 additional slow test that is not listed here.
TXT;
        }

        return <<<TXT

There are {$additionalSlowTestCount} additional slow tests that are not listed here.
TXT;
    }
}
