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
final class Duration
{
    /**
     * @var int
     */
    private $seconds;

    /**
     * @var int
     */
    private $nanoseconds;

    private function __construct(
        int $seconds,
        int $nanoseconds
    ) {
        $this->seconds = $seconds;
        $this->nanoseconds = $nanoseconds;
    }

    /**
     * @throws Exception\InvalidNanoseconds
     * @throws Exception\InvalidSeconds
     */
    public static function fromSecondsAndNanoseconds(
        int $seconds,
        int $nanoseconds
    ): self {
        if (0 > $seconds) {
            throw Exception\InvalidSeconds::notGreaterThanOrEqualToZero($seconds);
        }

        if (0 > $nanoseconds) {
            throw Exception\InvalidNanoseconds::notGreaterThanOrEqualToZero($nanoseconds);
        }

        $maxNanoseconds = 999999999;

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
        if (0 > $milliseconds) {
            throw Exception\InvalidMilliseconds::notGreaterThanZero($milliseconds);
        }

        $seconds = \intdiv(
            $milliseconds,
            1000,
        );

        $nanoseconds = ($milliseconds - $seconds * 1000) * 1000000;

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

    public function add(self $other): self
    {
        $seconds = $this->seconds + $other->seconds;
        $nanoseconds = $this->nanoseconds + $other->nanoseconds;

        if (999999999 < $nanoseconds) {
            return new self(
                $seconds + 1,
                $nanoseconds - 1000000000,
            );
        }

        return new self(
            $seconds,
            $nanoseconds,
        );
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
