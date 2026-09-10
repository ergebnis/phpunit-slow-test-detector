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

namespace Ergebnis\PHPUnit\SlowTestDetector;

/**
 * @internal
 */
final class MaximumWidth
{
    /**
     * @var Width
     */
    private $width;

    private function __construct(Width $width)
    {
        $this->width = $width;
    }

    public static function minimum(): self
    {
        return new self(Width::fromInt(80));
    }

    public static function unlimited(): self
    {
        return new self(Width::fromInt(\PHP_INT_MAX));
    }

    /**
     * @throws Exception\InvalidMaximumWidth
     */
    public static function fromWidth(Width $width): self
    {
        $minimum = self::minimum();

        if ($minimum->width->isGreaterThan($width)) {
            throw Exception\InvalidMaximumWidth::lessThanMinimum(
                $width->toInt(),
                $minimum->width->toInt()
            );
        }

        return new self($width);
    }

    public function isUnlimited(): bool
    {
        return \PHP_INT_MAX === $this->width->toInt();
    }

    public function toWidth(): Width
    {
        return $this->width;
    }
}
