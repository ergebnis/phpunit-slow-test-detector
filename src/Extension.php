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

namespace Ergebnis\PHPUnit\SlowTestDetector;

use PHPUnit\Runner;
use PHPUnit\TextUI;
use PHPUnit\Util;

try {
    $phpUnitVersionSeries = Version\Series::fromString(Runner\Version::series());
} catch (\InvalidArgumentException $exception) {
    throw new \RuntimeException(\sprintf(
        'Unable to determine PHPUnit version from version series "%s".',
        Runner\Version::series(),
    ));
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
        private int $suites = 0;
        private Duration $maximumDuration;
        private Collector\Collector $collector;
        private Reporter\Reporter $reporter;
        private Logger\Logger $logger;

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
            $durationFormatter = new Formatter\DefaultDurationFormatter();
            $this->reporter = new Reporter\DefaultReporter(
                $durationFormatter,
                $maximumDuration,
                $maximumCount,
            );
            $loggerFactory = new Logger\LoggerFactory(
                $durationFormatter,
            );
            $this->logger = $loggerFactory->forArguments($_SERVER['argv'], $options);
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
            $testFile = $this->resolveTestFile($test);

            $slowTest = SlowTest::create(
                $testIdentifier,
                $testFile,
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

            $this->logger->log(...$slowTests);

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

        private function resolveTestFile(string $test): TestFile
        {
            $test = \explode(' ', $test, 2)[0];

            try {
                $methodReflection = new \ReflectionMethod($test);
            } catch (\ReflectionException $e) {
                return TestFile::fromFilename($test);
            }

            return TestFile::fromFilenameAndLine(
                $methodReflection->getFileName(),
                $methodReflection->getStartLine(),
            );
        }
    }

    return;
}

if ($phpUnitVersionSeries->major()->isOneOf(Version\Major::fromInt(10), Version\Major::fromInt(11))) {
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
            $durationFormatter = new Formatter\DefaultDurationFormatter();
            $reporter = $configuration->noOutput()
                ? new Reporter\NullReporter()
                : new Reporter\DefaultReporter(
                    $durationFormatter,
                    $maximumDuration,
                    $maximumCount,
                );
            $loggerFactory = new Logger\LoggerFactory(
                $durationFormatter,
            );

            $facade->registerSubscribers(
                new Subscriber\Test\PreparationStartedSubscriber($timeKeeper),
                new Subscriber\Test\FinishedSubscriber(
                    $maximumDuration,
                    $timeKeeper,
                    $collector,
                ),
                new Subscriber\TestRunner\ExecutionFinishedSubscriber(
                    $collector,
                    $reporter,
                    $loggerFactory->forConfiguration(
                        $configuration,
                        $parameters,
                    ),
                ),
            );
        }
    }

    return;
}

    throw new \RuntimeException(\sprintf(
        'Unable to select extension for PHPUnit version with version series "%s".',
        Runner\Version::series(),
    ));
