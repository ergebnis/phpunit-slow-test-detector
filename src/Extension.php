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

namespace Ergebnis\PHPUnit\SlowTestDetector;

use Ergebnis\PHPUnit;
use PHPUnit\Framework;
use PHPUnit\Runner;
use PHPUnit\TextUI;
use PHPUnit\Util;

try {
    $phpUnitVersionSeries = Version\Series::fromString(Runner\Version::series());
} catch (\InvalidArgumentException $exception) {
    throw new \RuntimeException(\sprintf(
        'Unable to determine PHPUnit version from version series "%s".',
        Runner\Version::series()
    ));
}

if ($phpUnitVersionSeries->major()->equals(Version\Major::fromInt(6))) {
    final class Extension implements Framework\TestListener
    {
        /**
         * @var int
         */
        private $suites = 0;

        /**
         * @var Duration
         */
        private $maximumDuration;

        /**
         * @var Collector\Collector
         */
        private $collector;

        /**
         * @var Reporter\Reporter
         */
        private $reporter;

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
                $maximumCount
            );
        }

        public function addError(
            Framework\Test $test,
            \Exception $e,
            $time
        ) {
        }

        public function addWarning(
            Framework\Test $test,
            Framework\Warning $e,
            $time
        ) {
        }

        public function addFailure(
            Framework\Test $test,
            Framework\AssertionFailedError $e,
            $time
        ) {
        }

        public function addIncompleteTest(
            Framework\Test $test,
            \Exception $e,
            $time
        ) {
        }

        public function addRiskyTest(
            Framework\Test $test,
            \Exception $e,
            $time
        ) {
        }

        public function addSkippedTest(
            Framework\Test $test,
            \Exception $e,
            $time
        ) {
        }

        public function startTestSuite(Framework\TestSuite $suite)
        {
            ++$this->suites;
        }

        public function endTestSuite(Framework\TestSuite $suite)
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

        public function startTest(Framework\Test $test)
        {
        }

        public function endTest(
            Framework\Test $test,
            $time
        ) {
            $seconds = (int) \floor($time);
            $nanoseconds = (int) (($time - $seconds) * 1000000000);

            $duration = Duration::fromSecondsAndNanoseconds(
                $seconds,
                $nanoseconds
            );

            $maximumDuration = $this->resolveMaximumDuration($test);

            if (!$duration->isGreaterThan($maximumDuration)) {
                return;
            }

            $testIdentifier = TestIdentifier::fromString(\sprintf(
                '%s::%s',
                \get_class($test),
                $test->getName()
            ));

            $testDescription = TestDescription::fromString(\sprintf(
                '%s::%s',
                \get_class($test),
                $test->getName()
            ));

            $slowTest = SlowTest::create(
                $testIdentifier,
                $testDescription,
                $duration,
                $maximumDuration
            );

            $this->collector->collect($slowTest);
        }

        private function resolveMaximumDuration(Framework\Test $test): Duration
        {
            $annotations = [
                'maximumDuration',
                'slowThreshold',
            ];

            $symbolAnnotations = Util\Test::parseTestMethodAnnotations(
                \get_class($test),
                $test->getName(false)
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

if ($phpUnitVersionSeries->major()->isOneOf(Version\Major::fromInt(7), Version\Major::fromInt(8), Version\Major::fromInt(9))) {
    /**
     * @internal
     */
    final class Extension implements
        Runner\AfterLastTestHook,
        Runner\AfterSuccessfulTestHook,
        Runner\AfterTestHook,
        Runner\BeforeFirstTestHook
    {
        /**
         * @var int
         */
        private $suites = 0;

        /**
         * @var Duration
         */
        private $maximumDuration;

        /**
         * @var Collector\Collector
         */
        private $collector;

        /**
         * @var Reporter\Reporter
         */
        private $reporter;

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
                $maximumCount
            );
        }

        public function executeBeforeFirstTest(): void
        {
            ++$this->suites;
        }

        /**
         * @see https://github.com/sebastianbergmann/phpunit/pull/3392#issuecomment-1868311482
         * @see https://github.com/sebastianbergmann/phpunit/blob/7.5.0/src/TextUI/TestRunner.php#L227-L239
         * @see https://github.com/sebastianbergmann/phpunit/pull/3762
         */
        public function executeAfterSuccessfulTest(
            string $test,
            float $time
        ): void {
            // intentionally left blank
        }

        public function executeAfterTest(
            string $test,
            float $time
        ): void {
            $seconds = (int) \floor($time);
            $nanoseconds = (int) (($time - $seconds) * 1000000000);

            $duration = Duration::fromSecondsAndNanoseconds(
                $seconds,
                $nanoseconds
            );

            $maximumDuration = $this->resolveMaximumDuration($test);

            if (!$duration->isGreaterThan($maximumDuration)) {
                return;
            }

            $testIdentifier = TestIdentifier::fromString($test);
            $testDescription = TestDescription::fromString($test);

            $slowTest = SlowTest::create(
                $testIdentifier,
                $testDescription,
                $duration,
                $maximumDuration
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
            list($testClassName, $testMethodName) = \explode(
                '::',
                $test
            );

            $annotations = [
                'maximumDuration',
                'slowThreshold',
            ];

            $symbolAnnotations = Util\Test::parseTestMethodAnnotations(
                $testClassName,
                $testMethodName
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

if ($phpUnitVersionSeries->major()->isOneOf(Version\Major::fromInt(10), Version\Major::fromInt(11), Version\Major::fromInt(12))) {
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
                $maximumCount
            );

            $facade->registerSubscribers(
                new Subscriber\Test\PreparationStartedSubscriber($timeKeeper),
                new Subscriber\Test\FinishedSubscriber(
                    $maximumDuration,
                    $timeKeeper,
                    $collector,
                    Version\Series::fromString(Runner\Version::series())
                ),
                new Subscriber\TestRunner\ExecutionFinishedSubscriber(
                    $collector,
                    $reporter
                )
            );
        }
    }

    return;
}

throw new \RuntimeException(\sprintf(
    'Unable to select extension for PHPUnit version with version series "%s".',
    Runner\Version::series()
));
