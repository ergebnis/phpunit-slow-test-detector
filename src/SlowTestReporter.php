<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector;

use PHPUnit\Event;

final class SlowTestReporter
{
    private Comparator\DurationComparator $durationComparator;

    private Event\Telemetry\DurationFormatter $durationFormatter;

    private int $maximumNumber;

    private Event\Telemetry\Duration $maximumDuration;

    /**
     * @throws Exception\MaximumNumberNotGreaterThanZero
     */
    public function __construct(
        Event\Telemetry\DurationFormatter $durationFormatter,
        Event\Telemetry\Duration $maximumDuration,
        int $maximumNumber
    ) {
        if (0 >= $maximumNumber) {
            throw Exception\MaximumNumberNotGreaterThanZero::create($maximumNumber);
        }

        $this->durationComparator = new Comparator\DurationComparator();
        $this->durationFormatter = $durationFormatter;
        $this->maximumDuration = $maximumDuration;
        $this->maximumNumber = $maximumNumber;
    }

    public function report(SlowTest ...$slowTests): string
    {
        if ([] === $slowTests) {
            return '';
        }

        $header = $this->header(...$slowTests);
        $list = $this->list(...$slowTests);
        $footer = $this->footer(...$slowTests);

        return <<<TXT
{$header}
{$list}
{$footer}
TXT;
    }

    private function header(SlowTest ...$slowTests): string
    {
        $count = \count($slowTests);

        $formattedMaximumDuration = $this->durationFormatter->format($this->maximumDuration);

        return <<<TXT
Detected {$count} tests that took longer than {$formattedMaximumDuration}.

TXT;
    }

    private function list(SlowTest ...$slowTests): string
    {
        $durationComparator = $this->durationComparator;

        \usort($slowTests, static function (SlowTest $one, SlowTest $two) use ($durationComparator): int {
            return $durationComparator->compare(
                $two->duration(),
                $one->duration()
            );
        });

        $slowTestsToReport = \array_slice(
            $slowTests,
            0,
            $this->maximumNumber
        );

        /** @var SlowTest $slowestTest */
        $slowestTest = \reset($slowTestsToReport);

        $durationFormatter = $this->durationFormatter;

        $width = \strlen($durationFormatter->format($slowestTest->duration()));

        $items = \array_map(static function (SlowTest $slowTest) use ($durationFormatter, $width): string {
            $label = \str_pad(
                $durationFormatter->format($slowTest->duration()),
                $width,
                ' ',
                \STR_PAD_LEFT
            );

            $test = $slowTest->test();

            $testName = \sprintf(
                '%s::%s',
                $test->className(),
                $test->methodNameWithDataSet()
            );

            return <<<TXT
{$label}: {$testName}
TXT;
        }, $slowTestsToReport);

        return \implode(
            "\n",
            $items
        );
    }

    private function footer(SlowTest ...$slowTests): string
    {
        $remainingCount = \max(
            \count($slowTests) - $this->maximumNumber,
            0
        );

        if (0 === $remainingCount) {
            return '';
        }

        if (1 === $remainingCount) {
            return <<<'TXT'

There is one additional slow test that is not listed here.
TXT;
        }

        return <<<TXT

There are {$remainingCount} additional slow tests that are not listed here.
TXT;
    }
}
