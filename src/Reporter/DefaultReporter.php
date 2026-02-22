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

namespace Ergebnis\PHPUnit\SlowTestDetector\Reporter;

use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestList;

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

    public function __construct(
        Formatter\DurationFormatter $durationFormatter,
        MaximumDuration $maximumDuration,
        MaximumCount $maximumCount
    ) {
        $this->durationFormatter = $durationFormatter;
        $this->maximumDuration = $maximumDuration;
        $this->maximumCount = $maximumCount;
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

        $unit = Formatter\Unit::fromDurations(
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

        yield \sprintf(
            $headerTemplate,
            '#',
            'Duration',
            'Test'
        );

        $subHeaderTemplate = \sprintf(
            '%%%ds %%-%ds %%s',
            $numberColumnWidth,
            $durationColumnWidth
        );

        yield \sprintf(
            $subHeaderTemplate,
            '',
            'Actual',
            'Maximum'
        );

        $separator = \str_repeat(
            '-',
            $numberColumnWidth + 1 + $durationColumnWidth + 1 + $durationColumnWidth + 1 + $testDescriptionColumnWidth
        );

        yield $separator;

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

            yield \sprintf(
                $rowTemplate,
                $i + 1,
                $actualDurationFormatted,
                $maximumDurationFormatted,
                $slowTest->testDescription()->toString()
            );
        }

        yield $separator;

        yield from $this->legend(
            $unit,
            $numberColumnWidth + 1,
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

        $unit = Formatter\Unit::fromDurations(
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

        yield \sprintf(
            $headerTemplate,
            '#',
            'Duration',
            'Test'
        );

        $separator = \str_repeat(
            '-',
            $numberColumnWidth + 1 + $durationColumnWidth + 1 + $testDescriptionColumnWidth
        );

        yield $separator;

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

            yield \sprintf(
                $rowTemplate,
                $i + 1,
                $durationFormatted,
                $slowTest->testDescription()->toString()
            );
        }

        yield $separator;

        yield from $this->legend(
            $unit,
            $numberColumnWidth + 1,
            $durationColumnWidth
        );

        yield from $this->footer($slowTestCount);
    }

    private function durationColumnWidth(
        Formatter\Unit $unit,
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

    /**
     * @return \Generator<int, string>
     */
    private function legend(
        Formatter\Unit $unit,
        int $columnStart,
        int $columnWidth
    ): \Generator {
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

        yield $padding . $durationOfZeroFormatted;

        if ($unit->equals(Formatter\Unit::hours())) {
            yield $padding . ' │  │  └─── seconds';

            yield $padding . ' │  └────── minutes';

            yield $padding . ' └───────── hours';

            return;
        }

        if ($unit->equals(Formatter\Unit::minutes())) {
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
