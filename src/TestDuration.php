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
final class TestDuration
{
    private $duration;

    private function __construct(Duration $duration)
    {
        $this->duration = $duration;
    }

    public static function fromDuration(Duration $duration): self
    {
        return new self($duration);
    }

    public function toDuration(): Duration
    {
        return $this->duration;
    }
}
