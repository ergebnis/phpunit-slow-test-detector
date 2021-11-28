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

final class MaximumDuration
{
    private Event\Telemetry\Duration $duration;

    private function __construct(Event\Telemetry\Duration $duration)
    {
        $this->duration = $duration;
    }

    /**
     * @throws Exception\InvalidMaximumDuration
     */
    public static function fromSeconds(int $seconds): self
    {
        if (0 >= $seconds) {
            throw Exception\InvalidMaximumDuration::notGreaterThanZero($seconds);
        }

        return new self(Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $seconds,
            0,
        ));
    }

    /**
     * @throws Exception\InvalidMaximumDuration
     */
    public static function fromMilliseconds(int $milliseconds): self
    {
        if (0 >= $milliseconds) {
            throw Exception\InvalidMaximumDuration::notGreaterThanZero($milliseconds);
        }

        $seconds = \intdiv(
            $milliseconds,
            1_000,
        );

        $nanoseconds = ($milliseconds - $seconds * 1_000) * 1_000_000;

        return new self(Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $seconds,
            $nanoseconds,
        ));
    }

    public function toTelemetryDuration(): Event\Telemetry\Duration
    {
        return $this->duration;
    }
}
