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
final class MaximumDuration
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

    public static function default(): self
    {
        return new self(Duration::fromMilliseconds(500));
    }

    public function toDuration(): Duration
    {
        return $this->duration;
    }
}
