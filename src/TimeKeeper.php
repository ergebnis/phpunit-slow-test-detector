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

namespace Ergebnis\PHPUnit\SlowTestDetector;

use PHPUnit\Event;

final class TimeKeeper
{
    /**
     * @var array<string, Event\Telemetry\HRTime>
     */
    private array $startedTimes = [];

    public function start(Event\Code\Test $test, Event\Telemetry\HRTime $startedTime): void
    {
        $key = self::key($test);

        $this->startedTimes[$key] = $startedTime;
    }

    public function stop(Event\Code\Test $test, Event\Telemetry\HRTime $stoppedTime): Event\Telemetry\Duration
    {
        $key = self::key($test);

        if (!\array_key_exists($key, $this->startedTimes)) {
            return Event\Telemetry\Duration::fromSeconds(0);
        }

        $startedTime = $this->startedTimes[$key];

        unset($this->startedTimes[$key]);

        return $stoppedTime->duration($startedTime);
    }

    private static function key(Event\Code\Test $test): string
    {
        return \sprintf(
            '%s::%s',
            $test->className(),
            $test->methodNameWithDataSet(),
        );
    }
}
