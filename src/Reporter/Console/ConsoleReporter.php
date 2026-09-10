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
use Ergebnis\PHPUnit\SlowTestDetector\MaximumWidth;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestList;
use Ergebnis\PHPUnit\SlowTestDetector\Width;

/**
 * @internal
 */
final class ConsoleReporter implements Reporter\Reporter
{
    /**
     * @var Reporter\Console\DurationFormatter
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
     * @var MaximumWidth
     */
    private $maximumWidth;

    public function __construct(
        Reporter\Console\DurationFormatter $durationFormatter,
        MaximumDuration $maximumDuration,
        MaximumCount $maximumCount,
        MaximumWidth $maximumWidth
    ) {
        $this->durationFormatter = $durationFormatter;
        $this->maximumDuration = $maximumDuration;
        $this->maximumCount = $maximumCount;
        $this->maximumWidth = $maximumWidth;
    }

    public function report(SlowTestList $slowTestList): string
    {
        $lines = \iterator_to_array(
            $this->lines($slowTestList),
            false
        );

        if ([] === $lines) {
            return '';
        }

        return \implode(
            "\n",
            $lines
        );
    }

    /**
     * @return \Generator<int, string>
     */
    private function lines(SlowTestList $slowTestList): \Generator
    {
        $slowTestCount = $slowTestList->count();

        if ($slowTestCount->equals(Count::fromInt(0))) {
            return;
        }

        $slowTestListThatWillBeReported = $slowTestList
            ->sortByDurationDescending()
            ->limitTo($this->maximumCount);

        if ($slowTestListThatWillBeReported->hasSlowTestWithMaximumDurationDifferentFrom($this->maximumDuration->toDuration())) {
            yield from $this->reportWithCustomAndGlobalMaximumDuration(
                $slowTestCount,
                $slowTestListThatWillBeReported
            );

            return;
        }

        yield from $this->reportWithGlobalMaximumDuration(
            $slowTestCount,
            $slowTestListThatWillBeReported
        );
    }

    /**
     * @return \Generator<int, string>
     */
    private function reportWithCustomAndGlobalMaximumDuration(
        Count $slowTestCount,
        SlowTestList $slowTestListThatWillBeReported
    ): \Generator {
        yield '';

        yield '';

        $unit = Reporter\Console\Unit::fromDurations(
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

        yield \sprintf(
            'Detected %d %s where the duration exceeded a custom or the global maximum duration (%s).',
            $slowTestCount->toInt(),
            $slowTestCount->equals(Count::fromInt(1)) ? 'test' : 'tests',
            $globalMaximumDurationFormatted
        );

        yield '';

        $columnGap = Width::fromInt(1);
        $numberColumnWidth = Width::fromString((string) $slowTestListThatWillBeReported->count()->toInt());
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

        $testDescriptionColumnIndentation = Width::sum(
            $numberColumnWidth,
            $columnGap,
            $durationColumnWidth,
            $columnGap,
            $durationColumnWidth,
            $columnGap
        );

        $maximumTestDescriptionWidth = $this->maximumTestDescriptionWidth($testDescriptionColumnIndentation);
        $testDescriptionColumnWidth = self::testDescriptionColumnWidth(
            $slowTestListThatWillBeReported,
            $maximumTestDescriptionWidth
        );

        $durationHeaderWidth = Width::sum(
            $durationColumnWidth,
            $columnGap,
            $durationColumnWidth
        );

        $headerTemplate = \sprintf(
            '%%%ds %%-%ds %%s',
            $numberColumnWidth->toInt(),
            $durationHeaderWidth->toInt()
        );

        yield \sprintf(
            $headerTemplate,
            '#',
            'Duration',
            'Test'
        );

        $subHeaderTemplate = \sprintf(
            '%%%ds %%-%ds %%s',
            $numberColumnWidth->toInt(),
            $durationColumnWidth->toInt()
        );

        yield \sprintf(
            $subHeaderTemplate,
            '',
            'Actual',
            'Maximum'
        );

        $tableWidth = Width::sum(
            $testDescriptionColumnIndentation,
            $testDescriptionColumnWidth
        );

        $separator = \str_repeat(
            '-',
            $tableWidth->toInt()
        );

        yield $separator;

        $rowTemplate = \sprintf(
            '%%%dd %%%ds %%%ds %%s',
            $numberColumnWidth->toInt(),
            $durationColumnWidth->toInt(),
            $durationColumnWidth->toInt()
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

            yield \sprintf(
                $rowTemplate,
                $i + 1,
                $actualDurationFormatted,
                $maximumDurationFormatted,
                $slowTest->testDescription()->truncatedTo($maximumTestDescriptionWidth)->toString()
            );
        }

        yield $separator;

        $durationColumnIndentation = Width::sum(
            $numberColumnWidth,
            $columnGap
        );

        yield from $this->legend(
            $unit,
            $durationColumnIndentation,
            $durationColumnWidth
        );

        yield from $this->footer($slowTestCount);
    }

    /**
     * @return \Generator<int, string>
     */
    private function reportWithGlobalMaximumDuration(
        Count $slowTestCount,
        SlowTestList $slowTestListThatWillBeReported
    ): \Generator {
        yield '';

        yield '';

        $unit = Reporter\Console\Unit::fromDurations(
            $this->maximumDuration->toDuration(),
            ...\array_map(static function (SlowTest $slowTest): Duration {
                return $slowTest->duration();
            }, $slowTestListThatWillBeReported->toArray())
        );

        $globalMaximumDurationFormatted = $this->durationFormatter->format(
            $unit,
            $this->maximumDuration->toDuration()
        );

        yield \sprintf(
            'Detected %d %s where the duration exceeded the global maximum duration (%s).',
            $slowTestCount->toInt(),
            $slowTestCount->equals(Count::fromInt(1)) ? 'test' : 'tests',
            $globalMaximumDurationFormatted
        );

        yield '';

        $columnGap = Width::fromInt(1);
        $numberColumnWidth = Width::fromString((string) $slowTestListThatWillBeReported->count()->toInt());
        $durationColumnWidth = $this->durationColumnWidth(
            $unit,
            $this->maximumDuration->toDuration(),
            ...\array_map(static function (SlowTest $slowTest): Duration {
                return $slowTest->duration();
            }, $slowTestListThatWillBeReported->toArray())
        );
        $testDescriptionColumnIndentation = Width::sum(
            $numberColumnWidth,
            $columnGap,
            $durationColumnWidth,
            $columnGap
        );
        $maximumTestDescriptionWidth = $this->maximumTestDescriptionWidth($testDescriptionColumnIndentation);
        $testDescriptionColumnWidth = self::testDescriptionColumnWidth(
            $slowTestListThatWillBeReported,
            $maximumTestDescriptionWidth
        );

        $headerTemplate = \sprintf(
            '%%%ds %%-%ds %%s',
            $numberColumnWidth->toInt(),
            $durationColumnWidth->toInt()
        );

        yield \sprintf(
            $headerTemplate,
            '#',
            'Duration',
            'Test'
        );

        $tableWidth = Width::sum(
            $testDescriptionColumnIndentation,
            $testDescriptionColumnWidth
        );

        $separator = \str_repeat(
            '-',
            $tableWidth->toInt()
        );

        yield $separator;

        $rowTemplate = \sprintf(
            '%%%dd %%%ds %%s',
            $numberColumnWidth->toInt(),
            $durationColumnWidth->toInt()
        );

        foreach ($slowTestListThatWillBeReported->toArray() as $i => $slowTest) {
            $durationFormatted = $this->durationFormatter->format(
                $unit,
                $slowTest->duration()
            );

            yield \sprintf(
                $rowTemplate,
                $i + 1,
                $durationFormatted,
                $slowTest->testDescription()->truncatedTo($maximumTestDescriptionWidth)->toString()
            );
        }

        yield $separator;

        $durationColumnIndentation = Width::sum(
            $numberColumnWidth,
            $columnGap
        );

        yield from $this->legend(
            $unit,
            $durationColumnIndentation,
            $durationColumnWidth
        );

        yield from $this->footer($slowTestCount);
    }

    private function maximumTestDescriptionWidth(Width $testDescriptionColumnIndentation): Width
    {
        return $this->maximumWidth->toWidth()->minus($testDescriptionColumnIndentation);
    }

    private static function testDescriptionColumnWidth(
        SlowTestList $slowTestList,
        Width $maximumTestDescriptionWidth
    ): Width {
        $width = $slowTestList->sortByLengthOfTestDescriptionDescending()->first()->testDescription()->width();

        if ($width->isGreaterThan($maximumTestDescriptionWidth)) {
            return $maximumTestDescriptionWidth;
        }

        return $width;
    }

    private function durationColumnWidth(
        Reporter\Console\Unit $unit,
        Duration ...$durations
    ): Width {
        return Width::max(
            Width::fromString('Duration'),
            Width::fromString('Maximum'),
            ...\array_map(function (Duration $duration) use ($unit): Width {
                return Width::fromString($this->durationFormatter->format(
                    $unit,
                    $duration
                ));
            }, $durations)
        );
    }

    /**
     * @return \Generator<int, string>
     */
    private function legend(
        Reporter\Console\Unit $unit,
        Width $durationColumnIndentation,
        Width $durationColumnWidth
    ): \Generator {
        $durationOfZero = Duration::fromSecondsAndNanoseconds(
            0,
            0
        );

        $durationOfZeroFormatted = $this->durationFormatter->format(
            $unit,
            $durationOfZero
        );

        $paddingWidth = Width::sum(
            $durationColumnIndentation,
            $durationColumnWidth->minus(Width::fromString($durationOfZeroFormatted))
        );

        $padding = \str_repeat(
            ' ',
            $paddingWidth->toInt()
        );

        yield $padding . $durationOfZeroFormatted;

        if ($unit->equals(Reporter\Console\Unit::hours())) {
            yield $padding . ' │  │  └─── seconds';

            yield $padding . ' │  └────── minutes';

            yield $padding . ' └───────── hours';

            return;
        }

        if ($unit->equals(Reporter\Console\Unit::minutes())) {
            yield $padding . ' │  └─── seconds';

            yield $padding . ' └────── minutes';

            return;
        }

        yield $padding . ' └─── seconds';
    }

    /**
     * @return \Generator<int, string>
     */
    private function footer(Count $slowTestCount): \Generator
    {
        $additionalSlowTestCount = Count::fromInt(\max(
            0,
            $slowTestCount->toInt() - $this->maximumCount->toCount()->toInt()
        ));

        if ($additionalSlowTestCount->equals(Count::fromInt(0))) {
            return;
        }

        yield '';

        if ($additionalSlowTestCount->equals(Count::fromInt(1))) {
            yield 'There is 1 additional slow test that is not listed here.';
        } else {
            yield \sprintf(
                'There are %d additional slow tests that are not listed here.',
                $additionalSlowTestCount->toInt()
            );
        }
    }
}
