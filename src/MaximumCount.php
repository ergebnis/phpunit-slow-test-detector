<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector;

/**
 * @psalm-immutable
 */
final class MaximumCount
{
    private function __construct(private int $value)
    {
    }

    /**
     * @throws Exception\InvalidMaximumCount
     */
    public static function fromInt(int $value): self
    {
        if (0 >= $value) {
            throw Exception\InvalidMaximumCount::notGreaterThanZero($value);
        }

        return new self($value);
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
