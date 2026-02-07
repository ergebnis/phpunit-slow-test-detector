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
use Ergebnis\PHPUnit\SlowTestDetector\Formatter;
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
        $lines = \iterator_to_array($this->lines($slowTestList));

        if ([] === $lines) {
            return '';
        }

        return \implode('', \array_map(static function (string $line): string {
            return $line . "\n";
        }, $lines));
    }

    /**
     * @return \Generator<string>
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

        $globalMaximumDuration = $this->maximumDuration->toDuration();

        $hasCustomMaximumDuration = self::hasCustomMaximumDuration(
            $slowTestListThatWillBeReported,
            $globalMaximumDuration
        );

        yield '';

        yield '';

        yield $this->header(
            $slowTestCount,
            $globalMaximumDuration,
            $hasCustomMaximumDuration
        );

        yield '';

        if ($hasCustomMaximumDuration) {
            foreach ($this->listLinesWithCustomMaximumDuration($slowTestListThatWillBeReported, $globalMaximumDuration) as $line) {
                yield $line;
            }
        } else {
            foreach ($this->listLinesWithGlobalMaximumDurationOnly($slowTestListThatWillBeReported) as $line) {
                yield $line;
            }
        }

        yield '';

        $additionalSlowTestCount = Count::fromInt(\max(
            0,
            $slowTestCount->toInt() - $this->maximumCount->toCount()->toInt()
        ));

        if ($additionalSlowTestCount->equals(Count::fromInt(0))) {
            return;
        }

        if ($additionalSlowTestCount->equals(Count::fromInt(1))) {
            yield 'There is 1 additional slow test that is not listed here.';
        } else {
            yield \sprintf(
                'There are %d additional slow tests that are not listed here.',
                $additionalSlowTestCount->toInt()
            );
        }

        yield '';
    }

    private static function hasCustomMaximumDuration(
        SlowTestList $slowTestList,
        Duration $globalMaximumDuration
    ): bool {
        foreach ($slowTestList->toArray() as $slowTest) {
            $slowTestMaximumDuration = $slowTest->maximumDuration()->toDuration();

            if (!$slowTestMaximumDuration->equals($globalMaximumDuration)) {
                return true;
            }
        }

        return false;
    }

    private function header(
        Count $slowTestCount,
        Duration $globalMaximumDuration,
        bool $hasCustomMaximumDuration
    ): string {
        $formattedGlobalMaximumDuration = $this->durationFormatter->format($globalMaximumDuration);

        if ($hasCustomMaximumDuration) {
            if ($slowTestCount->equals(Count::fromInt(1))) {
                return \sprintf(
                    'Detected 1 test where the duration exceeded a custom or the global maximum duration (%s).',
                    $formattedGlobalMaximumDuration
                );
            }

            return \sprintf(
                'Detected %d tests where the duration exceeded a custom or the global maximum duration (%s).',
                $slowTestCount->toInt(),
                $formattedGlobalMaximumDuration
            );
        }

        if ($slowTestCount->equals(Count::fromInt(1))) {
            return \sprintf(
                'Detected 1 test where the duration exceeded the global maximum duration (%s).',
                $formattedGlobalMaximumDuration
            );
        }

        return \sprintf(
            'Detected %d tests where the duration exceeded the global maximum duration (%s).',
            $slowTestCount->toInt(),
            $formattedGlobalMaximumDuration
        );
    }

    /**
     * @return \Generator<string>
     */
    private function listLinesWithGlobalMaximumDurationOnly(SlowTestList $slowTestList): \Generator
    {
        $slowTestWithLongestDuration = $slowTestList->first();

        $numberWidth = \strlen((string) $slowTestList->count()->toInt());
        $durationWidth = \strlen($this->durationFormatter->format($slowTestWithLongestDuration->duration()));

        $template = \sprintf(
            '%%%dd. %%%ds %%s',
            $numberWidth,
            $durationWidth
        );

        $number = 1;

        foreach ($slowTestList->toArray() as $slowTest) {
            yield \sprintf(
                $template,
                (string) $number,
                $this->durationFormatter->format($slowTest->duration()),
                $slowTest->testDescription()->toString()
            );

            ++$number;
        }
    }

    /**
     * @return \Generator<string>
     */
    private function listLinesWithCustomMaximumDuration(
        SlowTestList $slowTestList,
        Duration $globalMaximumDuration
    ): \Generator {
        $slowTestWithLongestDuration = $slowTestList->first();
        $slowTestWithLongestMaximumDuration = $slowTestList->sortByMaximumDurationDescending()->first();

        $numberWidth = \strlen((string) $slowTestList->count()->toInt());
        $durationWidth = \strlen($this->durationFormatter->format($slowTestWithLongestDuration->duration()));
        $maximumDurationWidth = \strlen($this->durationFormatter->format($slowTestWithLongestMaximumDuration->maximumDuration()->toDuration()));

        $templateWithCustomMaximumDuration = \sprintf(
            '%%%dd. %%%ds (%%%ds) %%s',
            $numberWidth,
            $durationWidth,
            $maximumDurationWidth
        );

        $maximumDurationPadding = \str_repeat(' ', $maximumDurationWidth + 3);

        $templateWithGlobalMaximumDuration = \sprintf(
            '%%%dd. %%%ds %%s%%s',
            $numberWidth,
            $durationWidth
        );

        $number = 1;

        foreach ($slowTestList->toArray() as $slowTest) {
            $slowTestMaximumDuration = $slowTest->maximumDuration()->toDuration();

            if (!$slowTestMaximumDuration->equals($globalMaximumDuration)) {
                yield \sprintf(
                    $templateWithCustomMaximumDuration,
                    (string) $number,
                    $this->durationFormatter->format($slowTest->duration()),
                    $this->durationFormatter->format($slowTestMaximumDuration),
                    $slowTest->testDescription()->toString()
                );
            } else {
                yield \sprintf(
                    $templateWithGlobalMaximumDuration,
                    (string) $number,
                    $this->durationFormatter->format($slowTest->duration()),
                    $maximumDurationPadding,
                    $slowTest->testDescription()->toString()
                );
            }

            ++$number;
        }
    }
}
