<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Subscriber\Test;

use Ergebnis\PHPUnit\SlowTestDetector\Attribute;
use Ergebnis\PHPUnit\SlowTestDetector\Collector;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\TestFile;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use Ergebnis\PHPUnit\SlowTestDetector\Time;
use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use PHPUnit\Event;
use PHPUnit\Metadata;

/**
 * @internal
 */
final class FinishedSubscriber implements Event\Test\FinishedSubscriber
{
    private Collector\Collector $collector;
    private TimeKeeper $timeKeeper;
    private Duration $maximumDuration;

    public function __construct(
        Duration $maximumDuration,
        TimeKeeper $timeKeeper,
        Collector\Collector $collector
    ) {
        $this->maximumDuration = $maximumDuration;
        $this->timeKeeper = $timeKeeper;
        $this->collector = $collector;
    }

    /**
     * @see https://github.com/sebastianbergmann/phpunit/blob/10.0.0/src/Framework/TestRunner.php#L198
     * @see https://github.com/sebastianbergmann/phpunit/blob/10.0.0/src/Framework/TestRunner.php#L238
     */
    public function notify(Event\Test\Finished $event): void
    {
        $test = $event->test();
        $phaseIdentifier = PhaseIdentifier::fromString($test->id());

        $time = $event->telemetryInfo()->time();

        $phase = $this->timeKeeper->stop(
            $phaseIdentifier,
            Time::fromSecondsAndNanoseconds(
                $time->seconds(),
                $time->nanoseconds(),
            ),
        );

        $duration = $phase->duration();

        $maximumDuration = $this->resolveMaximumDuration($test);

        if (!$duration->isGreaterThan($maximumDuration)) {
            return;
        }

        $testFile = $test instanceof Event\Code\TestMethod
            ? TestFile::fromFilenameAndLine($test->file(), $test->line())
            : TestFile::fromFilename($test->file());

        $slowTest = SlowTest::create(
            TestIdentifier::fromString($test->id()),
            $testFile,
            $duration,
            $maximumDuration,
        );

        $this->collector->collect($slowTest);
    }

    private function resolveMaximumDuration(Event\Code\Test $test): Duration
    {
        $maximumDurationFromAttribute = self::resolveMaximumDurationFromAttribute($test);

        if ($maximumDurationFromAttribute instanceof Duration) {
            return $maximumDurationFromAttribute;
        }

        $maximumDurationFromAnnotation = self::resolveMaximumDurationFromAnnotation($test);

        if ($maximumDurationFromAnnotation instanceof Duration) {
            return $maximumDurationFromAnnotation;
        }

        return $this->maximumDuration;
    }

    private static function resolveMaximumDurationFromAttribute(Event\Code\Test $test): ?Duration
    {
        /** @var Event\Code\TestMethod $test */
        $methodReflection = new \ReflectionMethod(
            $test->className(),
            $test->methodName(),
        );

        $attributeReflections = $methodReflection->getAttributes(Attribute\MaximumDuration::class);

        if ([] !== $attributeReflections) {
            $attributeReflection = \reset($attributeReflections);

            $attribute = $attributeReflection->newInstance();

            return Duration::fromMilliseconds($attribute->milliseconds());
        }

        return null;
    }

    private static function resolveMaximumDurationFromAnnotation(Event\Code\Test $test): ?Duration
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

        return null;
    }
}
