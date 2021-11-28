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
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use PHPUnit\Event;
use PHPUnit\Util;

final class TestPassedSubscriber implements Event\Test\PassedSubscriber
{
    private MaximumDuration$maximumDuration;
    private TimeKeeper $timeKeeper;
    private Collector\Collector $collector;

    public function __construct(
        MaximumDuration $maximumDuration,
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
            $event->telemetryInfo()->time(),
        );

        $maximumDuration = $this->resolveMaximumDuration($event->test());

        if (!$duration->isGreaterThan($maximumDuration)) {
            return;
        }

        $slowTest = SlowTest::fromTestDurationAndMaximumDuration(
            $event->test(),
            $duration,
            $maximumDuration,
        );

        $this->collector->collect($slowTest);
    }

    private function resolveMaximumDuration(Event\Code\Test $test): Event\Telemetry\Duration
    {
        $annotations = Util\Test::parseTestMethodAnnotations(
            $test->className(),
            $test->methodName(),
        );

        if (!\array_key_exists('method', $annotations)) {
            return $this->maximumDuration->toTelemetryDuration();
        }

        if (!\is_array($annotations['method'])) {
            return $this->maximumDuration->toTelemetryDuration();
        }

        if (!\array_key_exists('slowThreshold', $annotations['method'])) {
            return $this->maximumDuration->toTelemetryDuration();
        }

        if (!\is_array($annotations['method']['slowThreshold'])) {
            return $this->maximumDuration->toTelemetryDuration();
        }

        $slowThreshold = \reset($annotations['method']['slowThreshold']);

        if (1 !== \preg_match('/^\d+$/', $slowThreshold)) {
            return $this->maximumDuration->toTelemetryDuration();
        }

        return MaximumDuration::fromMilliseconds((int) $slowThreshold)->toTelemetryDuration();
    }
}
