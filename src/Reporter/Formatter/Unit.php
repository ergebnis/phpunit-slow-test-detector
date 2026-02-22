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

namespace Ergebnis\PHPUnit\SlowTestDetector\Reporter\Formatter;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;

/**
 * @internal
 */
final class Unit
{
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function hours(): self
    {
        return new self('hours');
    }

    public static function minutes(): self
    {
        return new self('minutes');
    }

    public static function seconds(): self
    {
        return new self('seconds');
    }

    public static function fromDuration(Duration $duration): self
    {
        $totalSeconds = $duration->seconds();

        if (3600 <= $totalSeconds) {
            return self::hours();
        }

        if (60 <= $totalSeconds) {
            return self::minutes();
        }

        return self::seconds();
    }

    public static function fromDurations(Duration ...$durations): self
    {
        $largest = self::seconds();

        foreach ($durations as $duration) {
            $candidate = self::fromDuration($duration);

            if ($candidate->isGreaterThan($largest)) {
                $largest = $candidate;
            }
        }

        return $largest;
    }

    public function isGreaterThan(self $other): bool
    {
        return $this->rank() > $other->rank();
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    private function rank(): int
    {
        if ('hours' === $this->value) {
            return 2;
        }

        if ('minutes' === $this->value) {
            return 1;
        }

        return 0;
    }
}
