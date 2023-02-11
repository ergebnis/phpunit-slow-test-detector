<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2023 Andreas Möller
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
use PHPUnit\Metadata;

/**
 * @internal
 */
final class TestPassedSubscriber implements Event\Test\PassedSubscriber
{
    public function __construct(
        private readonly MaximumDuration $maximumDuration,
        private readonly TimeKeeper $timeKeeper,
        private readonly Collector\Collector $collector,
    ) {
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
        $annotations = [
            'maximumDuration',
            'slowThreshold',
        ];

        /** @var Event\Code\TestMethod $test */
        $docBlock = Metadata\Annotation\Parser\Registry::getInstance()->forMethod(
            $test->className(),
            $test->methodName(),
        );

        $symbolAnnotations = $docBlock->symbolAnnotations();

        foreach ($annotations as $annotation) {
            if (!\array_key_exists($annotation, $symbolAnnotations)) {
                continue;
            }

            if (!\is_array($symbolAnnotations[$annotation])) {
                continue;
            }

            $maximumDuration = \reset($symbolAnnotations[$annotation]);

            if (1 !== \preg_match('/^\d+$/', $maximumDuration)) {
                continue;
            }

            return MaximumDuration::fromMilliseconds((int) $maximumDuration)->toTelemetryDuration();
        }

        return $this->maximumDuration->toTelemetryDuration();
    }
}
