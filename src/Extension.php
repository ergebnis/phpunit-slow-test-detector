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
        Runner\Version::series(),
    ));
}

if ($phpUnitVersionSeries->major()->equals(Version\Major::fromInt(6))) {
    final class Extension implements Framework\TestListener
    {
        private int $suites = 0;
        private MaximumDuration $maximumDuration;
        private Collector\Collector $collector;
        private Reporter\Reporter $reporter;

        public function __construct(array $options = [])
        {
            $maximumCount = MaximumCount::default();

            if (\array_key_exists('maximum-count', $options)) {
                $maximumCount = MaximumCount::fromCount(Count::fromInt((int) $options['maximum-count']));
            }

            $maximumDuration = MaximumDuration::default();

            if (\array_key_exists('maximum-duration', $options)) {
                $maximumDuration = MaximumDuration::fromDuration(Duration::fromMilliseconds((int) $options['maximum-duration']));
            }

            $maximumWidth = MaximumWidth::unlimited();

            if (\array_key_exists('maximum-width', $options)) {
                if ('max' === $options['maximum-width']) {
                    $maximumWidth = MaximumWidth::fromWidth(Width::max(
                        MaximumWidth::minimum()->toWidth(),
                        Reporter\Console\Terminal::width()->toWidth(),
                    ));
                } else {
                    $maximumWidth = MaximumWidth::fromWidth(Width::fromInt((int) $options['maximum-width']));
                }
            }

            $gitHubActionsAnnotations = GitHubActionsAnnotations::disabled();

            if (\array_key_exists('github-actions-annotations', $options)) {
                if (true === $options['github-actions-annotations']) {
                    $gitHubActionsAnnotations = GitHubActionsAnnotations::enabled();
                } elseif (false === $options['github-actions-annotations']) {
                    $gitHubActionsAnnotations = GitHubActionsAnnotations::disabled();
                } else {
                    $gitHubActionsAnnotations = GitHubActionsAnnotations::fromString((string) $options['github-actions-annotations']);
                }
            }

            $this->maximumDuration = $maximumDuration;

            $target = 'php://stdout';

            if (
                \array_key_exists('stderr', $options)
                && true === $options['stderr']
            ) {
                $target = 'php://stderr';
            }

            $reporter = new Reporter\Console\ConsoleReporter(
                new Renderer\Printer(\fopen(
                    $target,
                    'wb',
                )),
                new Reporter\DurationFormatter(),
                $maximumDuration,
                $maximumCount,
                $maximumWidth,
            );

            if ($gitHubActionsAnnotations->isOneOf(
                GitHubActionsAnnotations::auto(),
                GitHubActionsAnnotations::enabled(),
            )) {
                $ciDetector = new CiDetector\Detector();

                if (
                    $gitHubActionsAnnotations->equals(GitHubActionsAnnotations::enabled())
                    || $ciDetector->isRunningOnGitHubActions(\getenv())
                ) {
                    $reporter = new Reporter\CompositeReporter(
                        $reporter,
                        new Reporter\GitHubActions\AnnotationReporter(
                            new Renderer\Printer(\fopen(
                                $target,
                                'wb',
                            )),
                            new Renderer\GitHubActions\WorkflowCommandRenderer(),
                            new Reporter\DurationFormatter(),
                            $maximumCount,
                        ),
                    );
                }
            }

            $this->collector = new Collector\DefaultCollector();
            $this->reporter = $reporter;
        }

        public function addError(
            Framework\Test $test,
            \Exception $e,
            $time
        ): void {
        }

        public function addWarning(
            Framework\Test $test,
            Framework\Warning $e,
            $time
        ): void {
        }

        public function addFailure(
            Framework\Test $test,
            Framework\AssertionFailedError $e,
            $time
        ): void {
        }

        public function addIncompleteTest(
            Framework\Test $test,
            \Exception $e,
            $time
        ): void {
        }

        public function addRiskyTest(
            Framework\Test $test,
            \Exception $e,
            $time
        ): void {
        }

        public function addSkippedTest(
            Framework\Test $test,
            \Exception $e,
            $time
        ): void {
        }

        public function startTestSuite(Framework\TestSuite $suite): void
        {
            ++$this->suites;
        }

        public function endTestSuite(Framework\TestSuite $suite): void
        {
            --$this->suites;

            if (0 < $this->suites) {
                return;
            }

            $slowTestList = $this->collector->slowTestList();

            if ($slowTestList->isEmpty()) {
                return;
            }

            $this->reporter->report($slowTestList);
        }

        public function startTest(Framework\Test $test): void
        {
        }

        public function endTest(
            Framework\Test $test,
            $time
        ): void {
            $seconds = (int) \floor($time);
            $nanoseconds = (int) (($time - $seconds) * 1000000000);

            $duration = Duration::fromSecondsAndNanoseconds(
                $seconds,
                $nanoseconds,
            );

            $maximumDuration = $this->resolveMaximumDuration($test);

            if (!$duration->isGreaterThan($maximumDuration->toDuration())) {
                return;
            }

            $slowTest = SlowTest::create(
                TestIdentifier::fromString(\sprintf(
                    '%s::%s',
                    \get_class($test),
                    $test->getName(),
                )),
                TestDescription::fromString(\sprintf(
                    '%s::%s',
                    \get_class($test),
                    $test->getName(),
                )),
                $duration,
                $maximumDuration,
            );

            $this->collector->collectSlowTest($slowTest);
        }

        private function resolveMaximumDuration(Framework\Test $test): MaximumDuration
        {
            $annotations = [
                'maximumDuration',
                'slowThreshold',
            ];

            $symbolAnnotations = Util\Test::parseTestMethodAnnotations(
                \get_class($test),
                $test->getName(false),
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

                return MaximumDuration::fromDuration(Duration::fromMilliseconds((int) $maximumDuration));
            }

            return $this->maximumDuration;
        }
    }

    return;
}

if ($phpUnitVersionSeries->major()->isOneOf(
    Version\Major::fromInt(7),
    Version\Major::fromInt(8),
    Version\Major::fromInt(9),
)) {
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
        private MaximumDuration $maximumDuration;
        private Collector\Collector $collector;
        private Reporter\Reporter $reporter;

        public function __construct(array $options = [])
        {
            $maximumCount = MaximumCount::default();

            if (\array_key_exists('maximum-count', $options)) {
                $maximumCount = MaximumCount::fromCount(Count::fromInt((int) $options['maximum-count']));
            }

            $maximumDuration = MaximumDuration::default();

            if (\array_key_exists('maximum-duration', $options)) {
                $maximumDuration = MaximumDuration::fromDuration(Duration::fromMilliseconds((int) $options['maximum-duration']));
            }

            $maximumWidth = MaximumWidth::unlimited();

            if (\array_key_exists('maximum-width', $options)) {
                if ('max' === $options['maximum-width']) {
                    $maximumWidth = MaximumWidth::fromWidth(Width::max(
                        MaximumWidth::minimum()->toWidth(),
                        Reporter\Console\Terminal::width()->toWidth(),
                    ));
                } else {
                    $maximumWidth = MaximumWidth::fromWidth(Width::fromInt((int) $options['maximum-width']));
                }
            }

            $gitHubActionsAnnotations = GitHubActionsAnnotations::disabled();

            if (\array_key_exists('github-actions-annotations', $options)) {
                if (true === $options['github-actions-annotations']) {
                    $gitHubActionsAnnotations = GitHubActionsAnnotations::enabled();
                } elseif (false === $options['github-actions-annotations']) {
                    $gitHubActionsAnnotations = GitHubActionsAnnotations::disabled();
                } else {
                    $gitHubActionsAnnotations = GitHubActionsAnnotations::fromString((string) $options['github-actions-annotations']);
                }
            }

            $this->maximumDuration = $maximumDuration;

            $target = 'php://stdout';

            if (
                \array_key_exists('stderr', $options)
                && true === $options['stderr']
            ) {
                $target = 'php://stderr';
            }

            $reporter = new Reporter\Console\ConsoleReporter(
                new Renderer\Printer(\fopen(
                    $target,
                    'wb',
                )),
                new Reporter\DurationFormatter(),
                $maximumDuration,
                $maximumCount,
                $maximumWidth,
            );

            if ($gitHubActionsAnnotations->isOneOf(
                GitHubActionsAnnotations::auto(),
                GitHubActionsAnnotations::enabled(),
            )) {
                $ciDetector = new CiDetector\Detector();

                if (
                    $gitHubActionsAnnotations->equals(GitHubActionsAnnotations::enabled())
                    || $ciDetector->isRunningOnGitHubActions(\getenv())
                ) {
                    $reporter = new Reporter\CompositeReporter(
                        $reporter,
                        new Reporter\GitHubActions\AnnotationReporter(
                            new Renderer\Printer(\fopen(
                                $target,
                                'wb',
                            )),
                            new Renderer\GitHubActions\WorkflowCommandRenderer(),
                            new Reporter\DurationFormatter(),
                            $maximumCount,
                        ),
                    );
                }
            }

            $this->collector = new Collector\DefaultCollector();
            $this->reporter = $reporter;
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
                $nanoseconds,
            );

            $maximumDuration = $this->resolveMaximumDuration($test);

            if (!$duration->isGreaterThan($maximumDuration->toDuration())) {
                return;
            }

            $slowTest = SlowTest::create(
                TestIdentifier::fromString($test),
                TestDescription::fromString($test),
                $duration,
                $maximumDuration,
            );

            $this->collector->collectSlowTest($slowTest);
        }

        public function executeAfterLastTest(): void
        {
            --$this->suites;

            if (0 < $this->suites) {
                return;
            }

            $slowTestList = $this->collector->slowTestList();

            if ($slowTestList->isEmpty()) {
                return;
            }

            $this->reporter->report($slowTestList);
        }

        private function resolveMaximumDuration(string $test): MaximumDuration
        {
            /**
             * @see https://github.com/sebastianbergmann/phpunit/blob/6.5.0/src/Framework/TestCase.php#L352-L368
             * @see https://github.com/sebastianbergmann/phpunit/blob/6.5.0/src/Framework/TestCase.php#L1966-L1992
             */
            $dataSetPosition = \strpos(
                $test,
                ' with data set',
            );

            if (false !== $dataSetPosition) {
                $test = \substr(
                    $test,
                    0,
                    $dataSetPosition,
                );
            }

            if (\strpos($test, '::') === false) {
                return $this->maximumDuration;
            }

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

                return MaximumDuration::fromDuration(Duration::fromMilliseconds((int) $maximumDuration));
            }

            return $this->maximumDuration;
        }
    }

    return;
}

if ($phpUnitVersionSeries->major()->isOneOf(
    Version\Major::fromInt(10),
    Version\Major::fromInt(11),
    Version\Major::fromInt(12),
    Version\Major::fromInt(13),
)) {
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

            $maximumCount = MaximumCount::default();

            if ($parameters->has('maximum-count')) {
                $maximumCount = MaximumCount::fromCount(Count::fromInt((int) $parameters->get('maximum-count')));
            }

            $maximumDuration = MaximumDuration::default();

            if ($parameters->has('maximum-duration')) {
                $maximumDuration = MaximumDuration::fromDuration(Duration::fromMilliseconds((int) $parameters->get('maximum-duration')));
            }

            $maximumWidth = MaximumWidth::unlimited();

            if ($parameters->has('maximum-width')) {
                if ('max' === $parameters->get('maximum-width')) {
                    $maximumWidth = MaximumWidth::fromWidth(Width::max(
                        MaximumWidth::minimum()->toWidth(),
                        Reporter\Console\Terminal::width()->toWidth(),
                    ));
                } else {
                    $maximumWidth = MaximumWidth::fromWidth(Width::fromInt((int) $parameters->get('maximum-width')));
                }
            }

            $gitHubActionsAnnotations = GitHubActionsAnnotations::disabled();

            if ($parameters->has('github-actions-annotations')) {
                $gitHubActionsAnnotations = GitHubActionsAnnotations::fromString($parameters->get('github-actions-annotations'));
            }

            $timeKeeper = new TimeKeeper();
            $collector = new Collector\DefaultCollector();

            $target = 'php://stdout';

            if ($configuration->outputToStandardErrorStream()) {
                $target = 'php://stderr';
            }

            $reporter = new Reporter\Console\ConsoleReporter(
                new Renderer\Printer(\fopen(
                    $target,
                    'wb',
                )),
                new Reporter\DurationFormatter(),
                $maximumDuration,
                $maximumCount,
                $maximumWidth,
            );

            if ($gitHubActionsAnnotations->isOneOf(
                GitHubActionsAnnotations::auto(),
                GitHubActionsAnnotations::enabled(),
            )) {
                $ciDetector = new CiDetector\Detector();

                if (
                    $gitHubActionsAnnotations->equals(GitHubActionsAnnotations::enabled())
                    || $ciDetector->isRunningOnGitHubActions(\getenv())
                ) {
                    $reporter = new Reporter\CompositeReporter(
                        $reporter,
                        new Reporter\GitHubActions\AnnotationReporter(
                            new Renderer\Printer(\fopen(
                                $target,
                                'wb',
                            )),
                            new Renderer\GitHubActions\WorkflowCommandRenderer(),
                            new Reporter\DurationFormatter(),
                            $maximumCount,
                        ),
                    );
                }
            }

            $facade->registerSubscribers(
                new Subscriber\Test\PreparationStartedSubscriber($timeKeeper),
                new Subscriber\Test\FinishedSubscriber(
                    $maximumDuration,
                    $timeKeeper,
                    $collector,
                    Version\Series::fromString(Runner\Version::series()),
                ),
                new Subscriber\Application\FinishedSubscriber(
                    $collector,
                    $reporter,
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
