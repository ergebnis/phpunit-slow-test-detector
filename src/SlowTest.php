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

final class SlowTest
{
    private Event\Code\Test $test;
    private Event\Telemetry\Duration $duration;
    private Event\Telemetry\Duration $maximumDuration;

    private function __construct(
        Event\Code\Test $test,
        Event\Telemetry\Duration $duration,
        Event\Telemetry\Duration $maximumDuration,
    ) {
        $this->test = $test;
        $this->duration = $duration;
        $this->maximumDuration = $maximumDuration;
    }

    public static function fromTestDurationAndMaximumDuration(
        Event\Code\Test $test,
        Event\Telemetry\Duration $duration,
        Event\Telemetry\Duration $maximumDuration,
    ): self {
        return new self(
            $test,
            $duration,
            $maximumDuration,
        );
    }

    public function test(): Event\Code\Test
    {
        return $this->test;
    }

    public function duration(): Event\Telemetry\Duration
    {
        return $this->duration;
    }

    public function maximumDuration(): Event\Telemetry\Duration
    {
        return $this->maximumDuration;
    }
}
