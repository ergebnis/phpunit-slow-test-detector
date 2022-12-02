<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Subscriber;

use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use PHPUnit\Event;

final class TestPreparedSubscriber implements Event\Test\PreparedSubscriber
{
    public function __construct(private readonly TimeKeeper $timeKeeper)
    {
    }

    public function notify(Event\Test\Prepared $event): void
    {
        $this->timeKeeper->start(
            $event->test(),
            $event->telemetryInfo()->time(),
        );
    }
}
