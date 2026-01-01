<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2026 Andreas Möller
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
final class Phase
{
    /**
     * @var PhaseIdentifier
     */
    private $phaseIdentifier;

    /**
     * @var Time
     */
    private $startTime;

    /**
     * @var Time
     */
    private $stopTime;

    /**
     * @var Duration
     */
    private $duration;

    private function __construct(
        PhaseIdentifier $phaseIdentifier,
        Time $startTime,
        Time $stopTime,
        Duration $duration
    ) {
        $this->phaseIdentifier = $phaseIdentifier;
        $this->startTime = $startTime;
        $this->stopTime = $stopTime;
        $this->duration = $duration;
    }

    public static function create(
        PhaseIdentifier $phaseIdentifier,
        Time $startTime,
        Time $stopTime
    ): self {
        return new self(
            $phaseIdentifier,
            $startTime,
            $stopTime,
            $stopTime->duration($startTime)
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

    public function stopTime(): Time
    {
        return $this->stopTime;
    }

    public function duration(): Duration
    {
        return $this->duration;
    }
}
