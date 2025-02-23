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

namespace Ergebnis\PHPUnit\SlowTestDetector;

/**
 * @internal
 */
final class SlowTest
{
    /**
     * @var TestIdentifier
     */
    private $testIdentifier;

    /**
     * @var TestDescription
     */
    private $testDescription;

    /**
     * @var Duration
     */
    private $duration;

    /**
     * @var MaximumDuration
     */
    private $maximumDuration;

    private function __construct(
        TestIdentifier $testIdentifier,
        TestDescription $testDescription,
        Duration $duration,
        MaximumDuration $maximumDuration
    ) {
        $this->testIdentifier = $testIdentifier;
        $this->testDescription = $testDescription;
        $this->duration = $duration;
        $this->maximumDuration = $maximumDuration;
    }

    public static function create(
        TestIdentifier $testIdentifier,
        TestDescription $testDescription,
        Duration $duration,
        MaximumDuration $maximumDuration
    ): self {
        return new self(
            $testIdentifier,
            $testDescription,
            $duration,
            $maximumDuration
        );
    }

    public function testIdentifier(): TestIdentifier
    {
        return $this->testIdentifier;
    }

    public function testDescription(): TestDescription
    {
        return $this->testDescription;
    }

    public function duration(): Duration
    {
        return $this->duration;
    }

    public function maximumDuration(): MaximumDuration
    {
        return $this->maximumDuration;
    }
}
