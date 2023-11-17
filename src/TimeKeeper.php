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

/**
 * @internal
 */
final class TimeKeeper
{
    /**
     * @var array<string, Time>
     */
    private array $startedTimes = [];

    public function start(
        TestIdentifier $testIdentifier,
        Time $startedTime,
    ): void {
        $key = $testIdentifier->toString();

        $this->startedTimes[$key] = $startedTime;
    }

    public function stop(
        TestIdentifier $testIdentifier,
        Time $stoppedTime,
    ): Duration {
        $key = $testIdentifier->toString();

        if (!\array_key_exists($key, $this->startedTimes)) {
            return Duration::fromSecondsAndNanoseconds(
                Seconds::fromInt(0),
                0,
            );
        }

        $startedTime = $this->startedTimes[$key];

        unset($this->startedTimes[$key]);

        return $stoppedTime->duration($startedTime);
    }
}
