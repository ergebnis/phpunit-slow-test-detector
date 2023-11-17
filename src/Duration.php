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
        private readonly Seconds $seconds,
        private readonly int $nanoseconds,
    ) {
    }

    /**
     * @throws Exception\InvalidNanoseconds
     */
    public static function fromSecondsAndNanoseconds(
        Seconds $seconds,
        int $nanoseconds,
    ): self {
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

        $seconds = Seconds::fromInt(\intdiv(
            $milliseconds,
            1_000,
        ));

        $nanoseconds = ($milliseconds - $seconds->toInt() * 1_000) * 1_000_000;

        return new self(
            $seconds,
            $nanoseconds,
        );
    }

    public function seconds(): Seconds
    {
        return $this->seconds;
    }

    public function nanoseconds(): int
    {
        return $this->nanoseconds;
    }

    public function isLessThan(self $other): bool
    {
        if ($this->seconds->toInt() < $other->seconds->toInt()) {
            return true;
        }

        if ($this->seconds->toInt() === $other->seconds->toInt()) {
            return $this->nanoseconds < $other->nanoseconds;
        }

        return false;
    }

    public function isGreaterThan(self $other): bool
    {
        if ($this->seconds->toInt() > $other->seconds->toInt()) {
            return true;
        }

        if ($this->seconds->toInt() === $other->seconds->toInt()) {
            return $this->nanoseconds > $other->nanoseconds;
        }

        return false;
    }
}
