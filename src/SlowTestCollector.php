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

final class SlowTestCollector
{
    private Event\Telemetry\Duration $maximumDuration;

    private TimeKeeper $timer;

    private Collector\Collector $collector;

    public function __construct(
        Event\Telemetry\Duration $maximumDuration,
        TimeKeeper $timeKeeper,
        Collector\Collector $collector
    ) {
        $this->maximumDuration = $maximumDuration;
        $this->timer = $timeKeeper;
        $this->collector = $collector;
    }

    public function testPrepared(Event\Code\Test $test, Event\Telemetry\HRTime $preparedTime): void
    {
        $this->timer->start(
            $test,
            $preparedTime
        );
    }

    public function testPassed(Event\Code\Test $test, Event\Telemetry\HRTime $passedTime): void
    {
        $duration = $this->timer->stop(
            $test,
            $passedTime
        );

        if (!$duration->isGreaterThan($this->maximumDuration)) {
            return;
        }

        $slowTest = SlowTest::fromTestAndDuration(
            $test,
            $duration
        );

        $this->collector->collect($slowTest);
    }

    public function maximumDuration(): Event\Telemetry\Duration
    {
        return $this->maximumDuration;
    }

    /**
     * @phpstan-return list<SlowTest>
     * @psalm-return list<SlowTest>
     *
     * @return array<int, SlowTest>
     */
    public function slowTests(): array
    {
        return $this->collector->collected();
    }
}
