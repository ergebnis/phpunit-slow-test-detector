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

namespace Ergebnis\PHPUnit\SlowTestDetector\Subscriber\TestRunner;

use Ergebnis\PHPUnit\SlowTestDetector\Collector;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use PHPUnit\Event;

/**
 * @internal
 */
final class ExecutionFinishedSubscriber implements Event\TestRunner\ExecutionFinishedSubscriber
{
    /**
     * @var Collector\Collector
     */
    private $collector;

    /**
     * @var Reporter\Reporter
     */
    private $reporter;

    public function __construct(
        Collector\Collector $collector,
        Reporter\Reporter $reporter
    ) {
        $this->collector = $collector;
        $this->reporter = $reporter;
    }

    /**
     * @see https://github.com/sebastianbergmann/phpunit/blob/10.0.0/src/TextUI/TestRunner.php#L65
     */
    public function notify(Event\TestRunner\ExecutionFinished $event): void
    {
        $slowTestList = $this->collector->slowTestList();

        if ($slowTestList->isEmpty()) {
            return;
        }

        $report = $this->reporter->report($slowTestList);

        if ('' === $report) {
            return;
        }

        echo <<<TXT


{$report}
TXT;
    }
}
