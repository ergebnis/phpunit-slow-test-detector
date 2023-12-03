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

namespace Ergebnis\PHPUnit\SlowTestDetector;

use PHPUnit\Runner;
use PHPUnit\TextUI;
use PHPUnit\Util;

try {
    $phpUnitVersion = Version\Version::fromString(Runner\Version::id());
} catch (\InvalidArgumentException $exception) {
    throw new \RuntimeException(\sprintf(
        'Unable to determine PHPUnit version from version identifier "%s".',
        Runner\Version::id(),
    ));
}

if ($phpUnitVersion->major()->equals(Version\Major::fromInt(9))) {
    /**
     * @internal
     */
    final class Extension implements
        Runner\AfterLastTestHook,
        Runner\AfterSuccessfulTestHook,
        Runner\BeforeFirstTestHook
    {
        private int $suites = 0;
        private Duration $maximumDuration;
        private Collector\Collector $collector;
        private Reporter\Reporter $reporter;

        public function __construct(array $options = [])
        {
            $maximumCount = Count::fromInt(10);

            if (\array_key_exists('maximum-count', $options)) {
                $maximumCount = Count::fromInt((int) $options['maximum-count']);
            }

            $maximumDuration = Duration::fromMilliseconds(500);

            if (\array_key_exists('maximum-duration', $options)) {
                $maximumDuration = Duration::fromMilliseconds((int) $options['maximum-duration']);
            }

            $this->maximumDuration = $maximumDuration;
            $this->collector = new Collector\DefaultCollector();
            $this->reporter = new Reporter\DefaultReporter(
                new Formatter\DefaultDurationFormatter(),
                $maximumDuration,
                $maximumCount,
            );
        }

        public function executeBeforeFirstTest(): void
        {
            ++$this->suites;
        }

        public function executeAfterSuccessfulTest(
            string $test,
            float $time
        ): void {
            $seconds = (int) \floor($time);
            $nanoseconds = (int) (($time - $seconds) * 1_000_000_000);

            $duration = Duration::fromSecondsAndNanoseconds(
                $seconds,
                $nanoseconds,
            );

            $maximumDuration = $this->resolveMaximumDuration($test);

            if (!$duration->isGreaterThan($maximumDuration)) {
                return;
            }

            $testIdentifier = TestIdentifier::fromString($test);

            $slowTest = SlowTest::create(
                $testIdentifier,
                $duration,
                $maximumDuration,
            );

            $this->collector->collect($slowTest);
        }

        public function executeAfterLastTest(): void
        {
            --$this->suites;

            if (0 < $this->suites) {
                return;
            }

            $slowTests = $this->collector->collected();

            if ([] === $slowTests) {
                return;
            }

            $report = $this->reporter->report(...$slowTests);

            if ('' === $report) {
                return;
            }

            echo <<<TXT


{$report}
TXT;
        }

        private function resolveMaximumDuration(string $test): Duration
        {
            [$testClassName, $testMethodName] = \explode(
                '::',
                $test,
            );

            $annotations = [
                'maximumDuration',
                'slowThreshold',
            ];

            $symbolAnnotations = Util\Test::parseTestMethodAnnotations(
                $testClassName,
                $testMethodName,
            );

            foreach ($annotations as $annotation) {
                if (!\is_array($symbolAnnotations['method'])) {
                    continue;
                }

                if (!\array_key_exists($annotation, $symbolAnnotations['method'])) {
                    continue;
                }

                if (!\is_array($symbolAnnotations['method'][$annotation])) {
                    continue;
                }

                $maximumDuration = \reset($symbolAnnotations['method'][$annotation]);

                if (1 !== \preg_match('/^\d+$/', $maximumDuration)) {
                    continue;
                }

                return Duration::fromMilliseconds((int) $maximumDuration);
            }

            return $this->maximumDuration;
        }
    }

    return;
}

if ($phpUnitVersion->major()->equals(Version\Major::fromInt(10))) {
    /**
     * @internal
     */
    final class Extension implements Runner\Extension\Extension
    {
        public function bootstrap(
            TextUI\Configuration\Configuration $configuration,
            Runner\Extension\Facade $facade,
            Runner\Extension\ParameterCollection $parameters
        ): void {
            if ($configuration->noOutput()) {
                return;
            }

            $maximumCount = Count::fromInt(10);

            if ($parameters->has('maximum-count')) {
                $maximumCount = Count::fromInt((int) $parameters->get('maximum-count'));
            }

            $maximumDuration = Duration::fromMilliseconds(500);

            if ($parameters->has('maximum-duration')) {
                $maximumDuration = Duration::fromMilliseconds((int) $parameters->get('maximum-duration'));
            }

            $timeKeeper = new TimeKeeper();
            $collector = new Collector\DefaultCollector();
            $reporter = new Reporter\DefaultReporter(
                new Formatter\DefaultDurationFormatter(),
                $maximumDuration,
                $maximumCount,
            );

            $facade->registerSubscribers(
                new Subscriber\TestPreparedSubscriber($timeKeeper),
                new Subscriber\TestPassedSubscriber(
                    $maximumDuration,
                    $timeKeeper,
                    $collector,
                ),
                new Subscriber\TestRunnerExecutionFinishedSubscriber(
                    $collector,
                    $reporter,
                ),
            );
        }
    }

    return;
}

    throw new \RuntimeException(\sprintf(
        'Unable to select extension for PHPUnit version with version identifier "%s".',
        Runner\Version::id(),
    ));
