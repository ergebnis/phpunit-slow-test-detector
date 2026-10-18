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

namespace Ergebnis\PHPUnit\SlowTestDetector\Reporter\GitHubActions;

use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\GitHubStepSummary;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\Renderer;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestList;

/**
 * @internal
 */
final class StepSummaryReporter implements Reporter\Reporter
{
    private GitHubStepSummary $gitHubStepSummary;
    private Renderer\Markdown\TableRenderer $tableRenderer;
    private Reporter\DurationFormatter $durationFormatter;
    private MaximumDuration $maximumDuration;
    private MaximumCount $maximumCount;

    public function __construct(
        GitHubStepSummary $gitHubStepSummary,
        Renderer\Markdown\TableRenderer $tableRenderer,
        Reporter\DurationFormatter $durationFormatter,
        MaximumDuration $maximumDuration,
        MaximumCount $maximumCount
    ) {
        $this->gitHubStepSummary = $gitHubStepSummary;
        $this->tableRenderer = $tableRenderer;
        $this->durationFormatter = $durationFormatter;
        $this->maximumDuration = $maximumDuration;
        $this->maximumCount = $maximumCount;
    }

    /**
     * @see https://docs.github.com/en/actions/reference/workflows-and-actions/workflow-commands#adding-a-job-summary
     */
    public function report(SlowTestList $slowTestList): void
    {
        if ($slowTestList->isEmpty()) {
            return;
        }

        $summary = $this->summary($slowTestList);

        $output = self::open($this->gitHubStepSummary);

        if (!\is_resource($output)) {
            return;
        }

        $printer = new Renderer\Printer($output);

        $printer->print($summary);

        \fclose($output);
    }

    private function summary(SlowTestList $slowTestList): string
    {
        $slowTestCount = $slowTestList->count();

        $slowTestListThatWillBeReported = $slowTestList
            ->sortByDurationDescending()
            ->limitTo($this->maximumCount);

        $unit = Reporter\Unit::seconds();

        $testOrTests = 'tests';

        if ($slowTestCount->equals(Count::fromInt(1))) {
            $testOrTests = 'test';
        }

        $maximumDurationDescription = 'the global maximum duration';

        if ($slowTestListThatWillBeReported->hasSlowTestWithMaximumDurationDifferentFrom($this->maximumDuration->toDuration())) {
            $maximumDurationDescription = 'a custom or the global maximum duration';
        }

        $lines = [
            '',
            '## Slow tests',
            '',
            \sprintf(
                'Detected %d %s where the duration exceeded %s (%s seconds).',
                $slowTestCount->toInt(),
                $testOrTests,
                $maximumDurationDescription,
                $this->durationFormatter->format(
                    $unit,
                    $this->maximumDuration->toDuration(),
                ),
            ),
            '',
        ];

        $table = Renderer\Markdown\Table::create(
            Renderer\Markdown\Column::create(
                '#',
                Renderer\Markdown\Alignment::right(),
            ),
            Renderer\Markdown\Column::create(
                'Duration',
                Renderer\Markdown\Alignment::right(),
            ),
            Renderer\Markdown\Column::create(
                'Maximum',
                Renderer\Markdown\Alignment::right(),
            ),
            Renderer\Markdown\Column::create(
                'Test',
                Renderer\Markdown\Alignment::left(),
            ),
        );

        foreach ($slowTestListThatWillBeReported->toArray() as $index => $slowTest) {
            $table = $table->withRow(
                (string) ($index + 1),
                $this->durationFormatter->format(
                    $unit,
                    $slowTest->duration(),
                ),
                $this->durationFormatter->format(
                    $unit,
                    $slowTest->maximumDuration()->toDuration(),
                ),
                Renderer\Markdown\InlineCode::fromString($slowTest->testDescription()->toString())->toString(),
            );
        }

        $summary = \implode(
            "\n",
            $lines,
        ) . "\n" . $this->tableRenderer->render($table);

        $additionalSlowTestCount = Count::fromInt(\max(
            0,
            $slowTestCount->toInt() - $this->maximumCount->toCount()->toInt(),
        ));

        if ($additionalSlowTestCount->equals(Count::fromInt(0))) {
            return $summary;
        }

        if ($additionalSlowTestCount->equals(Count::fromInt(1))) {
            return $summary . "\nThere is 1 additional slow test that is not listed here.\n";
        }

        return $summary . \sprintf(
            "\nThere are %d additional slow tests that are not listed here.\n",
            $additionalSlowTestCount->toInt(),
        );
    }

    /**
     * Opens the file for appending without letting a warning reach the output of PHPUnit when it cannot be opened.
     *
     * @return false|resource
     */
    private static function open(GitHubStepSummary $gitHubStepSummary)
    {
        \set_error_handler(static function (): bool {
            return true;
        });

        try {
            return \fopen(
                $gitHubStepSummary->toString(),
                'ab',
            );
        } finally {
            \restore_error_handler();
        }
    }
}
