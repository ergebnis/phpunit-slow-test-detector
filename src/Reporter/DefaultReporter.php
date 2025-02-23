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

use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Formatter;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
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
     * @var MaximumCount
     */
    private $maximumCount;

    public function __construct(
        Formatter\DurationFormatter $durationFormatter,
        MaximumCount $maximumCount
    ) {
        $this->durationFormatter = $durationFormatter;
        $this->maximumCount = $maximumCount;
    }

    public function report(SlowTestList $slowTestList): string
    {
        if ($slowTestList->isEmpty()) {
            return '';
        }

        return \implode("\n", \iterator_to_array($this->lines($slowTestList)));
    }

    /**
     * @return \Generator<string>
     */
    private function lines(SlowTestList $slowTestList): \Generator
    {
        $slowTestCount = $slowTestList->count();

        if ($slowTestCount->equals(Count::fromInt(1))) {
            yield 'Detected 1 test where the duration exceeded the maximum duration.';
        } else {
            yield \sprintf(
                'Detected %d tests where the duration exceeded the maximum duration.',
                $slowTestCount->toInt()
            );
        }

        yield '';

        $slowTestListThatWillBeReported = $slowTestList
            ->sortByDurationDescending()
            ->limitTo($this->maximumCount);

        $slowTestWithLongestDuration = $slowTestListThatWillBeReported->first();

        $slowTestWithLongestMaximumDuration = $slowTestListThatWillBeReported->sortByMaximumDurationDescending()->first();

        $numberWidth = \strlen((string) $slowTestListThatWillBeReported->count()->toInt());
        $durationWidth = \strlen($this->durationFormatter->format($slowTestWithLongestDuration->duration()));
        $maximumDurationWidth = \strlen($this->durationFormatter->format($slowTestWithLongestMaximumDuration->maximumDuration()->toDuration()));

        $template = \sprintf(
            '%%%dd. %%%ds (%%%ds) %%s',
            $numberWidth,
            $durationWidth,
            $maximumDurationWidth
        );

        $number = 1;

        foreach ($slowTestListThatWillBeReported->toArray() as $slowTest) {
            yield \sprintf(
                $template,
                (string) $number,
                $this->durationFormatter->format($slowTest->duration()),
                $this->durationFormatter->format($slowTest->maximumDuration()->toDuration()),
                $slowTest->testDescription()->toString()
            );

            ++$number;
        }

        $additionalSlowTestCount = Count::fromInt(\max(
            0,
            $slowTestList->count()->toInt() - $this->maximumCount->toCount()->toInt()
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
