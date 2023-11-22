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
    /**
     * @readonly
     */
    private int $seconds;

    /**
     * @readonly
     */
    private int $nanoseconds;

    private function __construct(int $seconds, int $nanoseconds)
    {
        $this->seconds = $seconds;
        $this->nanoseconds = $nanoseconds;
    }

    /**
     * @throws Exception\InvalidNanoseconds
     * @throws Exception\InvalidSeconds
     */
    public static function fromSecondsAndNanoseconds(int $seconds, int $nanoseconds): self
    {
        if (0 > $seconds) {
            throw Exception\InvalidSeconds::notGreaterThanOrEqualToZero($seconds);
        }

        if (0 > $nanoseconds) {
            throw Exception\InvalidNanoseconds::notGreaterThanOrEqualToZero($nanoseconds);
        }
        $maxNanoseconds = 999_999_999;

        if ($maxNanoseconds < $nanoseconds) {
            throw Exception\InvalidNanoseconds::notLessThanOrEqualTo(
                $nanoseconds,
                $maxNanoseconds,
            );
        }

        return new self(
            $seconds,
            $nanoseconds,
        );
    }

    /**
     * @throws Exception\InvalidMilliseconds
     */
    public static function fromMilliseconds(int $milliseconds): self
    {
        if (0 >= $milliseconds) {
            throw Exception\InvalidMilliseconds::notGreaterThanZero($milliseconds);
        }

        $seconds = \intdiv(
            $milliseconds,
            1_000,
        );

        $nanoseconds = ($milliseconds - $seconds * 1_000) * 1_000_000;

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

        if ($this->seconds === $other->seconds) {
            return $this->nanoseconds < $other->nanoseconds;
        }

        return false;
    }

    public function isGreaterThan(self $other): bool
    {
        if ($this->seconds > $other->seconds) {
            return true;
        }

        if ($this->seconds === $other->seconds) {
            return $this->nanoseconds > $other->nanoseconds;
        }

        return false;
    }
}
