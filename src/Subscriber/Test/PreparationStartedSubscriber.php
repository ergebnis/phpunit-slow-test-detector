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

namespace Ergebnis\PHPUnit\SlowTestDetector\Subscriber\Test;

use Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier;
use Ergebnis\PHPUnit\SlowTestDetector\Time;
use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use PHPUnit\Event;

/**
 * @internal
 */
final class PreparationStartedSubscriber implements Event\Test\PreparationStartedSubscriber
{
    /**
     * @var TimeKeeper
     */
    private $timeKeeper;

    public function __construct(TimeKeeper $timeKeeper)
    {
        $this->timeKeeper = $timeKeeper;
    }

    /**
     * @see https://github.com/sebastianbergmann/phpunit/blob/10.0.0/src/Framework/TestCase.php#L585-L587
     */
    public function notify(Event\Test\PreparationStarted $event): void
    {
        $time = $event->telemetryInfo()->time();

        $this->timeKeeper->start(
            PhaseIdentifier::fromString($event->test()->id()),
            Time::fromSecondsAndNanoseconds(
                $time->seconds(),
                $time->nanoseconds()
            )
        );
    }
}
