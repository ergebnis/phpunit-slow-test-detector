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
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestCount;
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

        return \implode("\n", \array_merge(
            $this->header($slowTestList),
            $this->list($slowTestList),
            $this->footer($slowTestList)
        ));
    }

    /**
     * @return list<string>
     */
    private function header(SlowTestList $slowTestList): array
    {
        $slowTestCount = $slowTestList->slowTestCount();

        if ($slowTestCount->toCount()->equals(Count::fromInt(1))) {
            return [
                'Detected 1 test where the duration exceeded the maximum duration.',
                '',
            ];
        }

        return [
            \sprintf(
                'Detected %d tests where the duration exceeded the maximum duration.',
                $slowTestCount->toCount()->toInt()
            ),
            '',
        ];
    }

    /**
     * @return list<string>
     */
    private function list(SlowTestList $slowTestList): array
    {
        $slowTestListThatWillBeReported = $slowTestList
            ->sortByTestDurationDescending()
            ->limitTo($this->maximumCount);

        $slowTestWithLongestTestDuration = $slowTestListThatWillBeReported->first();

        $slowTestWithLongestMaximumDuration = $slowTestListThatWillBeReported->sortByMaximumDurationDescending()->first();

        $durationFormatter = $this->durationFormatter;

        $numberWidth = \strlen((string) $slowTestListThatWillBeReported->slowTestCount()->toCount()->toInt());
        $testDurationWidth = \strlen($durationFormatter->format($slowTestWithLongestTestDuration->testDuration()->toDuration()));
        $maximumDurationWidth = \strlen($durationFormatter->format($slowTestWithLongestMaximumDuration->maximumDuration()->toDuration()));

        return \array_map(static function (int $number, SlowTest $slowTest) use ($numberWidth, $durationFormatter, $testDurationWidth, $maximumDurationWidth): string {
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
        }, \range(1, $slowTestListThatWillBeReported->slowTestCount()->toCount()->toInt()), $slowTestListThatWillBeReported->toArray());
    }

    /**
     * @return list<string>
     */
    private function footer(SlowTestList $slowTestList): array
    {
        $additionalSlowTestCount = SlowTestCount::fromCount(Count::fromInt(\max(
            0,
            $slowTestList->slowTestCount()->toCount()->toInt() - $this->maximumCount->toCount()->toInt()
        )));

        if ($additionalSlowTestCount->equals(SlowTestCount::fromCount(Count::fromInt(0)))) {
            return [];
        }

        if ($additionalSlowTestCount->equals(SlowTestCount::fromCount(Count::fromInt(1)))) {
            return [
                '',
                'There is 1 additional slow test that is not listed here.',
            ];
        }

        return [
            '',
            \sprintf(
                'There are %d additional slow tests that are not listed here.',
                $additionalSlowTestCount->toCount()->toInt()
            ),
        ];
    }
}
