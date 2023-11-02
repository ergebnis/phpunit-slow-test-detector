<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Reporter;

use Ergebnis\PHPUnit\SlowTestDetector\Comparator;
use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Formatter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;

/**
 * @internal
 */
final class DefaultReporter implements Reporter
{
    private readonly Comparator\DurationComparator $durationComparator;

    public function __construct(
        private readonly Formatter\DurationFormatter $durationFormatter,
        private readonly Duration $maximumDuration,
        private readonly Count $maximumCount,
    ) {
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
Detected {$count} test that took longer than expected.

TXT;
        }

        return <<<TXT
Detected {$count} tests that took longer than expected.

TXT;
    }

    private function list(SlowTest ...$slowTests): string
    {
        $durationComparator = $this->durationComparator;

        \usort($slowTests, static function (SlowTest $one, SlowTest $two) use ($durationComparator): int {
            return $durationComparator->compare(
                $two->duration(),
                $one->duration(),
            );
        });

        $slowTestsToReport = \array_slice(
            $slowTests,
            0,
            $this->maximumCount->toInt(),
        );

        /** @var SlowTest $slowestTest */
        $slowestTest = \reset($slowTestsToReport);

        $longestMaximumDuration = \array_reduce(
            $slowTestsToReport,
            static function (Duration $maximumDuration, SlowTest $slowTest): Duration {
                if ($maximumDuration->isLessThan($slowTest->maximumDuration())) {
                    return $slowTest->maximumDuration();
                }

                return $maximumDuration;
            },
            $this->maximumDuration,
        );

        $durationFormatter = $this->durationFormatter;

        $numberWidth = \strlen((string) \count($slowTestsToReport));
        $durationWidth = \strlen($durationFormatter->format($slowestTest->duration()));
        $maximumDurationWidth = \strlen($durationFormatter->format($longestMaximumDuration));

        $items = \array_map(static function (int $number, SlowTest $slowTest) use ($numberWidth, $durationFormatter, $durationWidth, $maximumDurationWidth): string {
            $formattedNumber = \str_pad(
                (string) $number,
                $numberWidth,
                ' ',
                \STR_PAD_LEFT,
            );

            $formattedDuration = \str_pad(
                $durationFormatter->format($slowTest->duration()),
                $durationWidth,
                ' ',
                \STR_PAD_LEFT,
            );

            $formattedMaximumDuration = \sprintf(
                '(%s)',
                \str_pad(
                    $durationFormatter->format($slowTest->maximumDuration()),
                    $maximumDurationWidth,
                    ' ',
                    \STR_PAD_LEFT,
                ),
            );

            $testName = $slowTest->test()->id();

            return <<<TXT
{$formattedNumber}. {$formattedDuration} {$formattedMaximumDuration} {$testName}
TXT;
        }, \range(1, \count($slowTestsToReport)), $slowTestsToReport);

        return \implode(
            "\n",
            $items,
        );
    }

    private function footer(SlowTest ...$slowTests): string
    {
        $remainingCount = \max(
            \count($slowTests) - $this->maximumCount->toInt(),
            0,
        );

        if (0 === $remainingCount) {
            return '';
        }

        if (1 === $remainingCount) {
            return <<<'TXT'

There is 1 additional slow test that is not listed here.
TXT;
        }

        return <<<TXT

There are {$remainingCount} additional slow tests that are not listed here.
TXT;
    }
}
