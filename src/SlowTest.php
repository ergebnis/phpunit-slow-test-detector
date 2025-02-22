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
     * @var TestDuration
     */
    private $testDuration;

    /**
     * @var MaximumDuration
     */
    private $maximumDuration;

    private function __construct(
        TestIdentifier $testIdentifier,
        TestDescription $testDescription,
        TestDuration $testDuration,
        MaximumDuration $maximumDuration
    ) {
        $this->testIdentifier = $testIdentifier;
        $this->testDescription = $testDescription;
        $this->testDuration = $testDuration;
        $this->maximumDuration = $maximumDuration;
    }

    public static function create(
        TestIdentifier $testIdentifier,
        TestDescription $testDescription,
        TestDuration $testDuration,
        MaximumDuration $maximumDuration
    ): self {
        return new self(
            $testIdentifier,
            $testDescription,
            $testDuration,
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

    public function testDuration(): TestDuration
    {
        return $this->testDuration;
    }

    public function maximumDuration(): MaximumDuration
    {
        return $this->maximumDuration;
    }
}
