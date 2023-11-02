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

final class Extension implements Runner\Extension\Extension
{
    public function bootstrap(
        TextUI\Configuration\Configuration $configuration,
        Runner\Extension\Facade $facade,
        Runner\Extension\ParameterCollection $parameters,
    ): void {
        if ($configuration->noOutput()) {
            return;
        }

        $maximumCount = MaximumCount::fromInt(10);

        if ($parameters->has('maximum-count')) {
            $maximumCount = MaximumCount::fromInt((int) $parameters->get('maximum-count'));
        }

        $maximumDuration = Duration::fromMilliseconds(500);

        if ($parameters->has('maximum-duration')) {
            $maximumDuration = Duration::fromMilliseconds((int) $parameters->get('maximum-duration'));
        }

        $collector = new Collector\DefaultCollector();

        $reporter = new Reporter\DefaultReporter(
            new Formatter\DefaultDurationFormatter(),
            $maximumDuration,
            $maximumCount,
        );

        $timeKeeper = new TimeKeeper();

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
