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

namespace Ergebnis\PHPUnit\SlowTestDetector\Subscriber;

use Ergebnis\PHPUnit\SlowTestDetector\Seconds;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use Ergebnis\PHPUnit\SlowTestDetector\Time;
use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use PHPUnit\Event;

/**
 * @internal
 */
final class TestPreparedSubscriber implements Event\Test\PreparedSubscriber
{
    public function __construct(private readonly TimeKeeper $timeKeeper)
    {
    }

    public function notify(Event\Test\Prepared $event): void
    {
        $time = $event->telemetryInfo()->time();

        $this->timeKeeper->start(
            TestIdentifier::fromString($event->test()->id()),
            Time::fromSecondsAndNanoseconds(
                Seconds::fromInt($time->seconds()),
                $time->nanoseconds(),
            ),
        );
    }
}
