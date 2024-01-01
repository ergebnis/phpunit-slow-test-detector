<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2024 Andreas Möller
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
final class PhaseStart
{
    private PhaseIdentifier $phaseIdentifier;
    private Time $startTime;

    private function __construct(
        PhaseIdentifier $phaseIdentifier,
        Time $startTime
    ) {
        $this->phaseIdentifier = $phaseIdentifier;
        $this->startTime = $startTime;
    }

    public static function create(
        PhaseIdentifier $phaseIdentifier,
        Time $startTime
    ): self {
        return new self(
            $phaseIdentifier,
            $startTime,
        );
    }

    public function phaseIdentifier(): PhaseIdentifier
    {
        return $this->phaseIdentifier;
    }

    public function startTime(): Time
    {
        return $this->startTime;
    }
}
