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

namespace Ergebnis\PHPUnit\SlowTestDetector\Subscriber\Test;

use Ergebnis\PHPUnit\SlowTestDetector\Attribute;
use Ergebnis\PHPUnit\SlowTestDetector\Collector;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\TestDescription;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use Ergebnis\PHPUnit\SlowTestDetector\Time;
use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use Ergebnis\PHPUnit\SlowTestDetector\Version;
use PHPUnit\Event;
use PHPUnit\Framework;
use PHPUnit\Metadata;

/**
 * @internal
 */
final class FinishedSubscriber implements Event\Test\FinishedSubscriber
{
    /**
     * @var MaximumDuration
     */
    private $maximumDuration;

    /**
     * @var TimeKeeper
     */
    private $timeKeeper;

    /**
     * @var Collector\Collector
     */
    private $collector;

    /**
     * @var Version\Series
     */
    private $versionSeries;

    public function __construct(
        MaximumDuration $maximumDuration,
        TimeKeeper $timeKeeper,
        Collector\Collector $collector,
        Version\Series $versionSeries
    ) {
        $this->maximumDuration = $maximumDuration;
        $this->timeKeeper = $timeKeeper;
        $this->collector = $collector;
        $this->versionSeries = $versionSeries;
    }

    /**
     * @see https://github.com/sebastianbergmann/phpunit/blob/10.0.0/src/Framework/TestRunner.php#L198
     * @see https://github.com/sebastianbergmann/phpunit/blob/10.0.0/src/Framework/TestRunner.php#L238
     */
    public function notify(Event\Test\Finished $event): void
    {
        $phaseIdentifier = PhaseIdentifier::fromString($event->test()->id());

        $time = $event->telemetryInfo()->time();

        $phase = $this->timeKeeper->stop(
            $phaseIdentifier,
            Time::fromSecondsAndNanoseconds(
                $time->seconds(),
                $time->nanoseconds()
            )
        );

        $duration = $phase->duration();

        $maximumDuration = $this->resolveMaximumDuration($event->test());

        if (!$duration->isGreaterThan($maximumDuration->toDuration())) {
            return;
        }

        $slowTest = SlowTest::create(
            TestIdentifier::fromString($event->test()->id()),
            self::descriptionFromTest($event->test()),
            $duration,
            $maximumDuration
        );

        $this->collector->collectSlowTest($slowTest);
    }

    /**
     * @see https://github.com/sebastianbergmann/phpunit/blob/11.1.3/src/TextUI/Output/Default/ResultPrinter.php#L511-L521
     */
    private static function descriptionFromTest(Event\Code\Test $test): TestDescription
    {
        if (!$test->isTestMethod()) {
            return TestDescription::fromString($test->name());
        }

        /** @var Event\Code\TestMethod $test */
        if (!$test->testData()->hasDataFromDataProvider()) {
            return TestDescription::fromString($test->nameWithClass());
        }

        $dataProvider = $test->testData()->dataFromDataProvider();

        /**
         * @see https://github.com/sebastianbergmann/phpunit/commit/5d049893b8
         */
        if (!\method_exists($dataProvider, 'dataAsStringForResultOutput')) {
            $dataAsStringForResultOutput = null;

            foreach (\debug_backtrace() as $frame) {
                if (!isset($frame['object'])) {
                    continue;
                }

                $object = $frame['object'];

                if (!$object instanceof Framework\TestCase) {
                    continue;
                }

                $dataAsStringForResultOutput = $object->dataSetAsStringWithData();
            }

            return TestDescription::fromString(\sprintf(
                '%s::%s%s',
                $test->className(),
                $test->methodName(),
                $dataAsStringForResultOutput
            ));
        }

        return TestDescription::fromString(\sprintf(
            '%s::%s%s',
            $test->className(),
            $test->methodName(),
            $test->testData()->dataFromDataProvider()->dataAsStringForResultOutput()
        ));
    }

    private function resolveMaximumDuration(Event\Code\Test $test): MaximumDuration
    {
        $maximumDurationFromAttribute = self::resolveMaximumDurationFromAttribute($test);

        if ($maximumDurationFromAttribute instanceof MaximumDuration) {
            return $maximumDurationFromAttribute;
        }

        if ($this->versionSeries->major()->isLessThan(Version\Major::fromInt(12))) {
            $maximumDurationFromAnnotation = self::resolveMaximumDurationFromAnnotation($test);

            if ($maximumDurationFromAnnotation instanceof MaximumDuration) {
                return $maximumDurationFromAnnotation;
            }
        }

        return $this->maximumDuration;
    }

    private static function resolveMaximumDurationFromAttribute(Event\Code\Test $test): ?MaximumDuration
    {
        /** @var Event\Code\TestMethod $test */
        $methodReflection = new \ReflectionMethod(
            $test->className(),
            $test->methodName()
        );

        $attributeReflections = $methodReflection->getAttributes(Attribute\MaximumDuration::class);

        if ([] !== $attributeReflections) {
            $attributeReflection = \reset($attributeReflections);

            $attribute = $attributeReflection->newInstance();

            return MaximumDuration::fromDuration(Duration::fromMilliseconds($attribute->milliseconds()));
        }

        return null;
    }

    private static function resolveMaximumDurationFromAnnotation(Event\Code\Test $test): ?MaximumDuration
    {
        $annotations = [
            'maximumDuration',
            'slowThreshold',
        ];

        /** @var Event\Code\TestMethod $test */
        $docBlock = Metadata\Annotation\Parser\Registry::getInstance()->forMethod(
            $test->className(),
            $test->methodName()
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

            return MaximumDuration::fromDuration(Duration::fromMilliseconds((int) $maximumDuration));
        }

        return null;
    }
}
