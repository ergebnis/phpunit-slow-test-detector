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
final class Time
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
                $maxNanoseconds
            );
        }

        return new self(
            $seconds,
            $nanoseconds
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

    /**
     * @throws Exception\InvalidStart
     */
    public function duration(self $start): Duration
    {
        $seconds = $this->seconds - $start->seconds;
        $nanoseconds = $this->nanoseconds - $start->nanoseconds;

        if (0 > $nanoseconds) {
            --$seconds;

            $nanoseconds += 1000000000;
        }

        if (0 > $seconds) {
            throw Exception\InvalidStart::notLessThanOrEqualToEnd();
        }

        return Duration::fromSecondsAndNanoseconds(
            $seconds,
            $nanoseconds
        );
    }
}
