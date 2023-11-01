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
final class Duration
{
    private function __construct(
        private readonly int $seconds,
        private readonly int $nanoseconds,
    ) {
    }

    /**
     * @throws Exception\InvalidNanoseconds
     * @throws Exception\InvalidSeconds
     */
    public static function fromSecondsAndNanoseconds(
        int $seconds,
        int $nanoseconds,
    ): self {
        if (0 > $seconds) {
            throw Exception\InvalidSeconds::notGreaterThanOrEqualToZero($seconds);
        }

        if (0 > $nanoseconds) {
            throw Exception\InvalidNanoseconds::notGreaterThanOrEqualToZero($nanoseconds);
        }

        if (999_999_999 < $nanoseconds) {
            throw Exception\InvalidNanoseconds::notLessThanOrEqualTo999999999($nanoseconds);
        }

        return new self(
            $seconds,
            $nanoseconds,
        );
    }

    public function seconds(): int
    {
        return $this->seconds;
    }

    public function nanoseconds(): int
    {
        return $this->nanoseconds;
    }

    public function isLessThan(self $other): bool
    {
        if ($this->seconds < $other->seconds) {
            return true;
        }

        if ($this->seconds > $other->seconds) {
            return false;
        }

        return $this->nanoseconds < $other->nanoseconds;
    }

    public function isGreaterThan(self $other): bool
    {
        if ($this->seconds > $other->seconds) {
            return true;
        }

        if ($this->seconds < $other->seconds) {
            return false;
        }

        return $this->nanoseconds > $other->nanoseconds;
    }
}
