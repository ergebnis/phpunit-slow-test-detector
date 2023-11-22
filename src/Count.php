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
final class Count
{
    /**
     * @readonly
     */
    private int $value;
    private function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @throws Exception\InvalidCount
     */
    public static function fromInt(int $value): self
    {
        if (0 >= $value) {
            throw Exception\InvalidCount::notGreaterThanZero($value);
        }

        return new self($value);
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
