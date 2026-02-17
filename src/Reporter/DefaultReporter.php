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

        $formattedGlobalMaximumDuration = $this->durationFormatter->format($globalMaximumDuration);

        yield '';

        yield '';

        if ($slowTestListThatWillBeReported->hasSlowTestWithMaximumDurationDifferentFrom($globalMaximumDuration)) {
            yield \sprintf(
                'Detected %d %s where the duration exceeded a custom or the global maximum duration (%s).',
                $slowTestCount->toInt(),
                $slowTestCount->equals(Count::fromInt(1)) ? 'test' : 'tests',
                $formattedGlobalMaximumDuration
            );

            yield '';

            $slowTestWithLongestDuration = $slowTestListThatWillBeReported->first();
            $slowTestWithLongestMaximumDuration = $slowTestListThatWillBeReported->sortByMaximumDurationDescending()->first();

            $numberWidth = \strlen((string) $slowTestListThatWillBeReported->count()->toInt());
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

            foreach ($slowTestListThatWillBeReported->toArray() as $slowTest) {
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
        } else {
            yield \sprintf(
                'Detected %d %s where the duration exceeded the global maximum duration (%s).',
                $slowTestCount->toInt(),
                $slowTestCount->equals(Count::fromInt(1)) ? 'test' : 'tests',
                $formattedGlobalMaximumDuration
            );

            yield '';

            $slowTestWithLongestDuration = $slowTestListThatWillBeReported->first();

            $numberWidth = \strlen((string) $slowTestListThatWillBeReported->count()->toInt());
            $durationWidth = \strlen($this->durationFormatter->format($slowTestWithLongestDuration->duration()));

            $template = \sprintf(
                '%%%dd. %%%ds %%s',
                $numberWidth,
                $durationWidth
            );

            $number = 1;

            foreach ($slowTestListThatWillBeReported->toArray() as $slowTest) {
                yield \sprintf(
                    $template,
                    (string) $number,
                    $this->durationFormatter->format($slowTest->duration()),
                    $slowTest->testDescription()->toString()
                );

                ++$number;
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
}
