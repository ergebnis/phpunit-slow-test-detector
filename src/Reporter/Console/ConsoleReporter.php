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

namespace Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console;

use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestList;

/**
 * @internal
 */
final class ConsoleReporter implements Reporter\Reporter
{
    /**
     * @var ConsolePrinter
     */
    private $printer;

    /**
     * @var DurationFormatter
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

    public function __construct(
        ConsolePrinter $printer,
        DurationFormatter $durationFormatter,
        MaximumDuration $maximumDuration,
        MaximumCount $maximumCount
    ) {
        $this->printer = $printer;
        $this->durationFormatter = $durationFormatter;
        $this->maximumDuration = $maximumDuration;
        $this->maximumCount = $maximumCount;
    }

    public function report(SlowTestList $slowTestList)
    {
        $slowTestCount = $slowTestList->count();

        if ($slowTestCount->equals(Count::fromInt(0))) {
            return;
        }

        $slowTestListThatWillBeReported = $slowTestList
            ->sortByDurationDescending()
            ->limitTo($this->maximumCount);

        if ($slowTestListThatWillBeReported->hasSlowTestWithMaximumDurationDifferentFrom($this->maximumDuration->toDuration())) {
            $this->reportWithCustomAndGlobalMaximumDuration(
                $slowTestCount,
                $slowTestListThatWillBeReported
            );

            return;
        }

        $this->reportWithGlobalMaximumDuration(
            $slowTestCount,
            $slowTestListThatWillBeReported
        );
    }

    private function reportWithCustomAndGlobalMaximumDuration(
        Count $slowTestCount,
        SlowTestList $slowTestListThatWillBeReported
    ) {
        $this->printer->printLine('');

        $this->printer->printLine('');

        $unit = Unit::fromDurations(
            $this->maximumDuration->toDuration(),
            ...\array_merge(
                \array_map(static function (SlowTest $slowTest): Duration {
                    return $slowTest->duration();
                }, $slowTestListThatWillBeReported->toArray()),
                \array_map(static function (SlowTest $slowTest): Duration {
                    return $slowTest->maximumDuration()->toDuration();
                }, $slowTestListThatWillBeReported->toArray())
            )
        );

        $globalMaximumDurationFormatted = $this->durationFormatter->format(
            $unit,
            $this->maximumDuration->toDuration()
        );

        $this->printer->printLine(\sprintf(
            'Detected %d %s where the duration exceeded a custom or the global maximum duration (%s).',
            $slowTestCount->toInt(),
            $slowTestCount->equals(Count::fromInt(1)) ? 'test' : 'tests',
            $globalMaximumDurationFormatted
        ));

        $this->printer->printLine('');

        $numberColumnWidth = \strlen((string) $slowTestListThatWillBeReported->count()->toInt());
        $durationColumnWidth = $this->durationColumnWidth(
            $unit,
            $this->maximumDuration->toDuration(),
            ...\array_merge(
                \array_map(static function (SlowTest $slowTest): Duration {
                    return $slowTest->duration();
                }, $slowTestListThatWillBeReported->toArray()),
                \array_map(static function (SlowTest $slowTest): Duration {
                    return $slowTest->maximumDuration()->toDuration();
                }, $slowTestListThatWillBeReported->toArray())
            )
        );
        $testDescriptionColumnWidth = \strlen($slowTestListThatWillBeReported->sortByLengthOfTestDescriptionDescending()->first()->testDescription()->toString());

        $headerTemplate = \sprintf(
            '%%%ds %%-%ds %%s',
            $numberColumnWidth,
            $durationColumnWidth + 1 + $durationColumnWidth
        );

        $this->printer->printLine(\sprintf(
            $headerTemplate,
            '#',
            'Duration',
            'Test'
        ));

        $subHeaderTemplate = \sprintf(
            '%%%ds %%-%ds %%s',
            $numberColumnWidth,
            $durationColumnWidth
        );

        $this->printer->printLine(\sprintf(
            $subHeaderTemplate,
            '',
            'Actual',
            'Maximum'
        ));

        $separator = \str_repeat(
            '-',
            $numberColumnWidth + 1 + $durationColumnWidth + 1 + $durationColumnWidth + 1 + $testDescriptionColumnWidth
        );

        $this->printer->printLine($separator);

        $rowTemplate = \sprintf(
            '%%%dd %%%ds %%%ds %%s',
            $numberColumnWidth,
            $durationColumnWidth,
            $durationColumnWidth
        );

        foreach ($slowTestListThatWillBeReported->toArray() as $i => $slowTest) {
            $actualDurationFormatted = $this->durationFormatter->format(
                $unit,
                $slowTest->duration()
            );

            $maximumDurationFormatted = '';

            $maximumDuration = $slowTest->maximumDuration()->toDuration();

            if (!$maximumDuration->equals($this->maximumDuration->toDuration())) {
                $maximumDurationFormatted = $this->durationFormatter->format(
                    $unit,
                    $maximumDuration
                );
            }

            $this->printer->printLine(\sprintf(
                $rowTemplate,
                $i + 1,
                $actualDurationFormatted,
                $maximumDurationFormatted,
                $slowTest->testDescription()->toString()
            ));
        }

        $this->printer->printLine($separator);

        $this->printLegend(
            $unit,
            $numberColumnWidth + 1,
            $durationColumnWidth
        );

        $this->printFooter($slowTestCount);
    }

    private function reportWithGlobalMaximumDuration(
        Count $slowTestCount,
        SlowTestList $slowTestListThatWillBeReported
    ) {
        $this->printer->printLine('');

        $this->printer->printLine('');

        $unit = Unit::fromDurations(
            $this->maximumDuration->toDuration(),
            ...\array_map(static function (SlowTest $slowTest): Duration {
                return $slowTest->duration();
            }, $slowTestListThatWillBeReported->toArray())
        );

        $globalMaximumDurationFormatted = $this->durationFormatter->format(
            $unit,
            $this->maximumDuration->toDuration()
        );

        $this->printer->printLine(\sprintf(
            'Detected %d %s where the duration exceeded the global maximum duration (%s).',
            $slowTestCount->toInt(),
            $slowTestCount->equals(Count::fromInt(1)) ? 'test' : 'tests',
            $globalMaximumDurationFormatted
        ));

        $this->printer->printLine('');

        $numberColumnWidth = \strlen((string) $slowTestListThatWillBeReported->count()->toInt());
        $durationColumnWidth = $this->durationColumnWidth(
            $unit,
            $this->maximumDuration->toDuration(),
            ...\array_map(static function (SlowTest $slowTest): Duration {
                return $slowTest->duration();
            }, $slowTestListThatWillBeReported->toArray())
        );
        $testDescriptionColumnWidth = \strlen($slowTestListThatWillBeReported->sortByLengthOfTestDescriptionDescending()->first()->testDescription()->toString());

        $headerTemplate = \sprintf(
            '%%%ds %%-%ds %%s',
            $numberColumnWidth,
            $durationColumnWidth
        );

        $this->printer->printLine(\sprintf(
            $headerTemplate,
            '#',
            'Duration',
            'Test'
        ));

        $separator = \str_repeat(
            '-',
            $numberColumnWidth + 1 + $durationColumnWidth + 1 + $testDescriptionColumnWidth
        );

        $this->printer->printLine($separator);

        $rowTemplate = \sprintf(
            '%%%dd %%%ds %%s',
            $numberColumnWidth,
            $durationColumnWidth
        );

        foreach ($slowTestListThatWillBeReported->toArray() as $i => $slowTest) {
            $durationFormatted = $this->durationFormatter->format(
                $unit,
                $slowTest->duration()
            );

            $this->printer->printLine(\sprintf(
                $rowTemplate,
                $i + 1,
                $durationFormatted,
                $slowTest->testDescription()->toString()
            ));
        }

        $this->printer->printLine($separator);

        $this->printLegend(
            $unit,
            $numberColumnWidth + 1,
            $durationColumnWidth
        );

        $this->printFooter($slowTestCount);
    }

    private function durationColumnWidth(
        Unit $unit,
        Duration ...$durations
    ): int {
        return \max(
            \strlen('Duration'),
            \strlen('Maximum'),
            ...\array_map(function (Duration $duration) use ($unit): int {
                $durationFormatted = $this->durationFormatter->format($unit, $duration);

                return \strlen($durationFormatted);
            }, $durations)
        );
    }

    private function printLegend(
        Unit $unit,
        int $columnStart,
        int $columnWidth
    ) {
        $durationOfZero = Duration::fromSecondsAndNanoseconds(
            0,
            0
        );

        $durationOfZeroFormatted = $this->durationFormatter->format(
            $unit,
            $durationOfZero
        );

        $padding = \str_repeat(
            ' ',
            $columnStart + $columnWidth - \strlen($durationOfZeroFormatted)
        );

        $this->printer->printLine($padding . $durationOfZeroFormatted);

        if ($unit->equals(Unit::hours())) {
            $this->printer->printLine($padding . ' │  │  └─── seconds');

            $this->printer->printLine($padding . ' │  └────── minutes');

            $this->printer->printLine($padding . ' └───────── hours');

            return;
        }

        if ($unit->equals(Unit::minutes())) {
            $this->printer->printLine($padding . ' │  └─── seconds');

            $this->printer->printLine($padding . ' └────── minutes');

            return;
        }

        $this->printer->printLine($padding . ' └─── seconds');
    }

    private function printFooter(Count $slowTestCount)
    {
        $additionalSlowTestCount = Count::fromInt(\max(
            0,
            $slowTestCount->toInt() - $this->maximumCount->toCount()->toInt()
        ));

        if ($additionalSlowTestCount->equals(Count::fromInt(0))) {
            return;
        }

        $this->printer->printLine('');

        if ($additionalSlowTestCount->equals(Count::fromInt(1))) {
            $this->printer->printLine('There is 1 additional slow test that is not listed here.');

            return;
        }

        $this->printer->printLine(\sprintf(
            'There are %d additional slow tests that are not listed here.',
            $additionalSlowTestCount->toInt()
        ));
    }
}
