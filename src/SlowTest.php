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
final class SlowTest
{
    private function __construct(
        private TestIdentifier $testIdentifier,
        private Duration $duration,
        private Duration $maximumDuration,
    ) {
    }

    public static function create(
        TestIdentifier $testIdentifier,
        Duration $duration,
        Duration $maximumDuration,
    ): self {
        return new self(
            $testIdentifier,
            $duration,
            $maximumDuration,
        );
    }

    public function testIdentifier(): TestIdentifier
    {
        return $this->testIdentifier;
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
