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
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
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

        yield \sprintf(
            'Detected %d %s where the duration exceeded a custom or the global maximum duration (%s).',
            $slowTestCount->toInt(),
            $slowTestCount->equals(Count::fromInt(1)) ? 'test' : 'tests',
            $this->durationFormatter->format($this->maximumDuration->toDuration())
        );

        yield '';

        $slowTestWithLongestActualDuration = $slowTestListThatWillBeReported->first();
        $slowTestWithLongestMaximumDuration = $slowTestListThatWillBeReported->sortByMaximumDurationDescending()->first();
        $slowTestWithLongestTestDescription = $slowTestListThatWillBeReported->sortByLengthOfTestDescriptionDescending()->first();

        $numberColumnWidth = \strlen((string) $slowTestListThatWillBeReported->count()->toInt());
        $actualDurationColumnWidth = \strlen($this->durationFormatter->format($slowTestWithLongestActualDuration->duration()));
        $maximumDurationColumnWidth = \strlen($this->durationFormatter->format($slowTestWithLongestMaximumDuration->maximumDuration()->toDuration()));
        $testDescriptionColumnWidth = \strlen($slowTestWithLongestTestDescription->testDescription()->toString());

        $headerTemplate = \sprintf(
            '%%%ds %%-%ds %%s',
            $numberColumnWidth,
            $actualDurationColumnWidth + 1 + $maximumDurationColumnWidth
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
            $actualDurationColumnWidth
        );

        yield \sprintf(
            $subHeaderTemplate,
            '',
            'Actual',
            'Maximum'
        );

        $separator = \str_repeat(
            '-',
            $numberColumnWidth + 1 + $actualDurationColumnWidth + 1 + $maximumDurationColumnWidth + 1 + $testDescriptionColumnWidth
        );

        yield $separator;

        $rowTemplate = \sprintf(
            '%%%dd %%%ds %%%ds %%s',
            $numberColumnWidth,
            $actualDurationColumnWidth,
            $maximumDurationColumnWidth
        );

        foreach ($slowTestListThatWillBeReported->toArray() as $i => $slowTest) {
            $slowTestMaximumDuration = $slowTest->maximumDuration()->toDuration();

            yield \sprintf(
                $rowTemplate,
                $i + 1,
                $this->durationFormatter->format($slowTest->duration()),
                $slowTestMaximumDuration->equals($this->maximumDuration->toDuration()) ? '' : $this->durationFormatter->format($slowTestMaximumDuration),
                $slowTest->testDescription()->toString()
            );
        }

        yield $separator;

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

        yield \sprintf(
            'Detected %d %s where the duration exceeded the global maximum duration (%s).',
            $slowTestCount->toInt(),
            $slowTestCount->equals(Count::fromInt(1)) ? 'test' : 'tests',
            $this->durationFormatter->format($this->maximumDuration->toDuration())
        );

        yield '';

        $slowTestWithLongestDuration = $slowTestListThatWillBeReported->first();
        $slowTestWithLongestTestDescription = $slowTestListThatWillBeReported->sortByLengthOfTestDescriptionDescending()->first();

        $numberColumnWidth = \strlen((string) $slowTestListThatWillBeReported->count()->toInt());
        $durationColumnWidth = \strlen($this->durationFormatter->format($slowTestWithLongestDuration->duration()));
        $testDescriptionColumnWidth = \strlen($slowTestWithLongestTestDescription->testDescription()->toString());

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
            yield \sprintf(
                $rowTemplate,
                $i + 1,
                $this->durationFormatter->format($slowTest->duration()),
                $slowTest->testDescription()->toString()
            );
        }

        yield $separator;

        yield from $this->footer($slowTestCount);
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
