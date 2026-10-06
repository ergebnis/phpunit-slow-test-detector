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

namespace Ergebnis\PHPUnit\SlowTestDetector\Subscriber\Application;

use Ergebnis\PHPUnit\SlowTestDetector\Collector;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use PHPUnit\Event;

/**
 * @internal
 */
final class FinishedSubscriber implements Event\Application\FinishedSubscriber
{
    private Collector\Collector $collector;
    private Reporter\Reporter $reporter;

    public function __construct(
        Collector\Collector $collector,
        Reporter\Reporter $reporter
    ) {
        $this->collector = $collector;
        $this->reporter = $reporter;
    }

    /**
     * @see https://github.com/sebastianbergmann/phpunit/blob/13.4.0/src/TextUI/Application.php
     */
    public function notify(Event\Application\Finished $event): void
    {
        $slowTestList = $this->collector->slowTestList();

        if ($slowTestList->isEmpty()) {
            return;
        }

        $this->reporter->report($slowTestList);
    }
}
