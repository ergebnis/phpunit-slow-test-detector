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

namespace Ergebnis\PHPUnit\SlowTestCollector;

use PHPUnit\Event;

final class SlowTest
{
    private Event\Code\Test $test;

    private Event\Telemetry\Duration $duration;

    private function __construct(
        Event\Code\Test $test,
        Event\Telemetry\Duration $duration
    ) {
        $this->test = $test;
        $this->duration = $duration;
    }

    public static function fromTestAndDuration(
        Event\Code\Test $test,
        Event\Telemetry\Duration $duration
    ): self {
        return new self(
            $test,
            $duration
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
}
