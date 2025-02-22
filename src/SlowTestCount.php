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
final class SlowTestCount
{
    private $count;

    private function __construct(Count $count)
    {
        $this->count = $count;
    }

    public static function fromCount(Count $count): self
    {
        return new self($count);
    }

    public function toCount(): Count
    {
        return $this->count;
    }

    public function equals(self $other): bool
    {
        return $this->count->equals($other->toCount());
    }
}
