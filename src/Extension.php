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
        $maximumCount = MaximumCount::fromInt(3);

        $maximumDuration = MaximumDuration::fromMilliseconds(125);

        if ($parameters->has('maximum-duration')) {
            $maximumDuration = MaximumDuration::fromMilliseconds((int) $parameters->get('maximum-duration'));
        }

        $collector = new Collector\DefaultCollector();

        $reporter = new Reporter\DefaultReporter(
            new Formatter\ToMillisecondsDurationFormatter(),
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
