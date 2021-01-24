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

namespace Ergebnis\PHPUnit\SlowTestDetector\Subscriber;

use Ergebnis\PHPUnit\SlowTestDetector\SlowTestCollector;
use PHPUnit\Event;

final class TestPassedSubscriber implements Event\Test\PassedSubscriber
{
    private SlowTestCollector $slowTestCollector;

    public function __construct(SlowTestCollector $slowTestCollector)
    {
        $this->slowTestCollector = $slowTestCollector;
    }

    public function notify(Event\Test\Passed $event): void
    {
        $this->slowTestCollector->testPassed(
            $event->test(),
            $event->telemetryInfo()->time()
        );
    }
}
