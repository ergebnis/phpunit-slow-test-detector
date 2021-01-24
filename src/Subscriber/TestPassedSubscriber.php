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

namespace Ergebnis\PHPUnit\SlowTestDetector\Subscriber;

use Ergebnis\PHPUnit\SlowTestDetector\Collector;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use PHPUnit\Event;

final class TestPassedSubscriber implements Event\Test\PassedSubscriber
{
    private Event\Telemetry\Duration $maximumDuration;

    private TimeKeeper $timeKeeper;

    private Collector\Collector $collector;

    public function __construct(
        Event\Telemetry\Duration $maximumDuration,
        TimeKeeper $timeKeeper,
        Collector\Collector $collector
    ) {
        $this->maximumDuration = $maximumDuration;
        $this->timeKeeper = $timeKeeper;
        $this->collector = $collector;
    }

    public function notify(Event\Test\Passed $event): void
    {
        $duration = $this->timeKeeper->stop(
            $event->test(),
            $event->telemetryInfo()->time()
        );

        if (!$duration->isGreaterThan($this->maximumDuration)) {
            return;
        }

        $slowTest = SlowTest::fromTestAndDuration(
            $event->test(),
            $duration
        );

        $this->collector->collect($slowTest);
    }
}
