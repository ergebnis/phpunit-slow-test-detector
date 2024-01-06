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
final class SlowTest
{
    private Duration $maximumDuration;
    private Duration $duration;
    private TestIdentifier $testIdentifier;
    private TestFile $testFile;

    private function __construct(
        TestIdentifier $testIdentifier,
        TestFile $testFile,
        Duration $duration,
        Duration $maximumDuration
    ) {
        $this->testIdentifier = $testIdentifier;
        $this->testFile = $testFile;
        $this->duration = $duration;
        $this->maximumDuration = $maximumDuration;
    }

    public static function create(
        TestIdentifier $testIdentifier,
        TestFile $testFile,
        Duration $duration,
        Duration $maximumDuration
    ): self {
        return new self(
            $testIdentifier,
            $testFile,
            $duration,
            $maximumDuration,
        );
    }

    public function testIdentifier(): TestIdentifier
    {
        return $this->testIdentifier;
    }

    public function testFile(): TestFile
    {
        return $this->testFile;
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
