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
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use Ergebnis\PHPUnit\SlowTestDetector\Time;
use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use PHPUnit\Event;
use PHPUnit\Metadata;

/**
 * @internal
 */
final class TestPassedSubscriber implements Event\Test\PassedSubscriber
{
    /**
     * @readonly
     */
    private Duration $maximumDuration;
    /**
     * @readonly
     */
    private TimeKeeper $timeKeeper;
    /**
     * @readonly
     */
    private Collector\Collector $collector;
    public function __construct(Duration $maximumDuration, TimeKeeper $timeKeeper, Collector\Collector $collector)
    {
        $this->maximumDuration = $maximumDuration;
        $this->timeKeeper = $timeKeeper;
        $this->collector = $collector;
    }
    public function notify(Event\Test\Passed $event): void
    {
        $testIdentifier = TestIdentifier::fromString($event->test()->id());

        $time = $event->telemetryInfo()->time();

        $duration = $this->timeKeeper->stop(
            $testIdentifier,
            Time::fromSecondsAndNanoseconds(
                $time->seconds(),
                $time->nanoseconds(),
            ),
        );

        $maximumDuration = $this->resolveMaximumDuration($event->test());

        if (!$duration->isGreaterThan($maximumDuration)) {
            return;
        }

        $slowTest = SlowTest::create(
            $testIdentifier,
            $duration,
            $maximumDuration,
        );

        $this->collector->collect($slowTest);
    }

    private function resolveMaximumDuration(Event\Code\Test $test): Duration
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

            return Duration::fromMilliseconds((int) $maximumDuration);
        }

        return $this->maximumDuration;
    }
}
