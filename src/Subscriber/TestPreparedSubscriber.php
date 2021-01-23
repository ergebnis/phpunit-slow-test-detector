<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-collector
 */

namespace Ergebnis\PHPUnit\SlowTestCollector\Subscriber;

use Ergebnis\PHPUnit\SlowTestCollector\SlowTestCollector;
use PHPUnit\Event;

final class TestPreparedSubscriber implements Event\Test\PreparedSubscriber
{
    private SlowTestCollector $slowTestCollector;

    public function __construct(SlowTestCollector $slowTestCollector)
    {
        $this->slowTestCollector = $slowTestCollector;
    }

    public function notify(Event\Test\Prepared $event): void
    {
        $this->slowTestCollector->testPrepared(
            $event->test(),
            $event->telemetryInfo()->time()
        );
    }
}
