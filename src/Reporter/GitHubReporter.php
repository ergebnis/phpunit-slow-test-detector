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
final class GitHubReporter implements Reporter
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
        $slowTestCount = $slowTestList->count();

        if ($slowTestCount->equals(Count::fromInt(0))) {
            return '';
        }

        $slowTestListThatWillBeReported = $slowTestList
            ->sortByDurationDescending()
            ->limitTo($this->maximumCount);

        $lines = [];

        foreach ($slowTestListThatWillBeReported->toArray() as $slowTest) {
            $unit = Formatter\Unit::fromDurations(
                $slowTest->duration(),
                $slowTest->maximumDuration()->toDuration()
            );

            $durationFormatted = $this->durationFormatter->format(
                $unit,
                $slowTest->duration()
            );

            $maximumDurationFormatted = $this->durationFormatter->format(
                $unit,
                $slowTest->maximumDuration()->toDuration()
            );

            $message = \sprintf(
                '%s took %s, maximum allowed is %s',
                $slowTest->testDescription()->toString(),
                $durationFormatted,
                $maximumDurationFormatted
            );

            $lines[] = \sprintf(
                '::warning title=Slow Test::%s',
                self::escape($message)
            );
        }

        return "\n" . \implode(
            "\n",
            $lines
        );
    }

    /**
     * @see https://github.com/actions/toolkit/blob/main/packages/core/src/command.ts
     */
    private static function escape(string $value): string
    {
        return \str_replace(
            [
                '%',
                "\r",
                "\n",
            ],
            [
                '%25',
                '%0D',
                '%0A',
            ],
            $value
        );
    }
}
