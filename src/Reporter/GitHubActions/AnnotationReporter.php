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

use Ergebnis\PHPUnit\SlowTestDetector\File;
use Ergebnis\PHPUnit\SlowTestDetector\GitHubWorkspace;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
use Ergebnis\PHPUnit\SlowTestDetector\Renderer;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestList;
use Ergebnis\PHPUnit\SlowTestDetector\TestLocation;

/**
 * @internal
 */
final class AnnotationReporter implements Reporter\Reporter
{
    private Renderer\Printer $printer;
    private Renderer\GitHubActions\WorkflowCommandRenderer $workflowCommandRenderer;
    private Reporter\DurationFormatter $durationFormatter;
    private MaximumCount $maximumCount;
    private ?GitHubWorkspace $gitHubWorkspace;

    public function __construct(
        Renderer\Printer $printer,
        Renderer\GitHubActions\WorkflowCommandRenderer $workflowCommandRenderer,
        Reporter\DurationFormatter $durationFormatter,
        MaximumCount $maximumCount,
        ?GitHubWorkspace $gitHubWorkspace
    ) {
        $this->printer = $printer;
        $this->workflowCommandRenderer = $workflowCommandRenderer;
        $this->durationFormatter = $durationFormatter;
        $this->maximumCount = $maximumCount;
        $this->gitHubWorkspace = $gitHubWorkspace;
    }

    /**
     * @see https://github.com/actions/toolkit/blob/bcae5eca3f432f591cf9f052db4533fc585fb2ac/docs/problem-matchers.md#limitations
     */
    public function report(SlowTestList $slowTestList): void
    {
        if ($slowTestList->isEmpty()) {
            return;
        }

        $slowTestListThatWillBeReported = $slowTestList
            ->sortByDurationDescending()
            ->limitTo($this->maximumCount);

        $unit = Reporter\Unit::seconds();

        $this->printer->print("\n" . \implode('', \array_map(function (SlowTest $slowTest) use ($unit): string {
            $warningMessage = Renderer\GitHubActions\WarningMessage::create(
                Renderer\GitHubActions\Title::fromString('Slow Test'),
                Renderer\GitHubActions\Message::fromString(\sprintf(
                    '%s took %s seconds, maximum is %s seconds',
                    $slowTest->testDescription()->toString(),
                    $this->durationFormatter->format(
                        $unit,
                        $slowTest->duration(),
                    ),
                    $this->durationFormatter->format(
                        $unit,
                        $slowTest->maximumDuration()->toDuration(),
                    ),
                )),
            );

            $testLocation = $slowTest->testLocation();

            if (!$testLocation instanceof TestLocation) {
                return $this->workflowCommandRenderer->render($warningMessage);
            }

            if (!$this->gitHubWorkspace instanceof GitHubWorkspace) {
                return $this->workflowCommandRenderer->render($warningMessage);
            }

            $file = $this->gitHubWorkspace->relativePathOf($testLocation->file());

            if (!$file instanceof File) {
                return $this->workflowCommandRenderer->render($warningMessage);
            }

            return $this->workflowCommandRenderer->render($warningMessage->withFileAndLine(
                $file,
                $testLocation->line(),
            ));
        }, $slowTestListThatWillBeReported->toArray())));
    }
}
