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

use PHPUnit\Event;

/**
 * @internal
 */
final class SlowTest
{
    private function __construct(
        private readonly Event\Code\Test $test,
        private readonly Duration $duration,
        private readonly Duration $maximumDuration,
    ) {
    }

    public static function fromTestDurationAndMaximumDuration(
        Event\Code\Test $test,
        Duration $duration,
        Duration $maximumDuration,
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

    public function duration(): Duration
    {
        return $this->duration;
    }

    public function maximumDuration(): Duration
    {
        return $this->maximumDuration;
    }
}
