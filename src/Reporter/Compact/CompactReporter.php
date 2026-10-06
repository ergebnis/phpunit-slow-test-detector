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

namespace Ergebnis\PHPUnit\SlowTestDetector\Reporter\Compact;

use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
use Ergebnis\PHPUnit\SlowTestDetector\Renderer;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestList;

/**
 * @internal
 *
 * @see https://github.com/sebastianbergmann/phpunit/pull/6597
 */
final class CompactReporter implements Reporter\Reporter
{
    private Renderer\Printer $printer;
    private Renderer\CompactRenderer $renderer;
    private Reporter\DurationFormatter $durationFormatter;
    private MaximumCount $maximumCount;

    public function __construct(
        Renderer\Printer $printer,
        Renderer\CompactRenderer $renderer,
        Reporter\DurationFormatter $durationFormatter,
        MaximumCount $maximumCount
    ) {
        $this->printer = $printer;
        $this->renderer = $renderer;
        $this->durationFormatter = $durationFormatter;
        $this->maximumCount = $maximumCount;
    }

    public function report(SlowTestList $slowTestList): void
    {
        $slowTestCount = $slowTestList->count();

        if ($slowTestCount->equals(Count::fromInt(0))) {
            return;
        }

        $slowTestListThatWillBeReported = $slowTestList
            ->sortByDurationDescending()
            ->limitTo($this->maximumCount);

        $this->printer->print($this->renderer->banner('ergebnis/phpunit-slow-test-detector'));

        foreach ($slowTestListThatWillBeReported->toArray() as $slowTest) {
            $this->printer->print($this->renderer->header(
                'SLOW',
                $slowTest->testDescription()->toString(),
            ));

            $this->printer->print($this->renderer->body(\sprintf(
                '%s seconds (maximum %s seconds)',
                $this->durationFormatter->format(
                    Reporter\Unit::seconds(),
                    $slowTest->duration(),
                ),
                $this->durationFormatter->format(
                    Reporter\Unit::seconds(),
                    $slowTest->maximumDuration()->toDuration(),
                ),
            )));
        }

        $counts = [
            \sprintf(
                '%d tests',
                $slowTestCount->toInt(),
            ),
        ];

        if ($slowTestCount->equals(Count::fromInt(1))) {
            $counts = [
                '1 test',
            ];
        }

        $additionalSlowTestCount = $slowTestCount->toInt() - $slowTestListThatWillBeReported->count()->toInt();

        if (0 < $additionalSlowTestCount) {
            $counts[] = \sprintf(
                '%d not listed',
                $additionalSlowTestCount,
            );
        }

        $this->printer->print($this->renderer->summary(
            'SLOW',
            $counts,
        ));
    }
}
