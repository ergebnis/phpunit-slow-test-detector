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
final class MaximumCount
{
    private $count;

    private function __construct(Count $count)
    {
        $this->count = $count;
    }

    /**
     * @throws Exception\InvalidMaximumCount
     */
    public static function fromCount(Count $count): self
    {
        if ($count->toInt() <= 0) {
            throw Exception\InvalidMaximumCount::notGreaterThanZero($count->toInt());
        }

        return new self($count);
    }

    public static function default(): self
    {
        return new self(Count::fromInt(10));
    }

    public function toCount(): Count
    {
        return $this->count;
    }
}
