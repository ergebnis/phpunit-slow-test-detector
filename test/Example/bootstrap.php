<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Ergebnis\PHPUnit\SlowTestDetector;
use PHPUnit\Event;

$maximumNumber = 3;

if (\is_string(\getenv('MAXIMUM_NUMBER'))) {
    $maximumNumber = (int) \getenv('MAXIMUM_NUMBER');
}

$maximumDuration = SlowTestDetector\MaximumDuration::fromMilliseconds(125);

$collector = new SlowTestDetector\Collector\DefaultCollector();

$reporter = new SlowTestDetector\Reporter\DefaultReporter(
    new SlowTestDetector\Formatter\ToMillisecondsDurationFormatter(),
    $maximumDuration,
    $maximumNumber
);

$timeKeeper = new SlowTestDetector\TimeKeeper();

Event\Facade::registerSubscriber(new SlowTestDetector\Subscriber\TestPreparedSubscriber($timeKeeper));

Event\Facade::registerSubscriber(new SlowTestDetector\Subscriber\TestPassedSubscriber(
    $maximumDuration,
    $timeKeeper,
    $collector
));

Event\Facade::registerSubscriber(new SlowTestDetector\Subscriber\TestSuiteFinishedSubscriber(
    $collector,
    $reporter
));
