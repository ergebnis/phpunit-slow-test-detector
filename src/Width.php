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
final class Width
{
    /**
     * @var int
     */
    private $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @throws Exception\InvalidWidth
     */
    public static function fromInt(int $value): self
    {
        if (0 > $value) {
            throw Exception\InvalidWidth::notGreaterThanOrEqualToZero($value);
        }

        return new self($value);
    }

    public static function fromString(string $value): self
    {
        $valueWithoutZeroWidthCharacters = \preg_replace(
            '/[\p{Mn}\p{Me}\p{Cf}]/u',
            '',
            $value
        );

        if (!\is_string($valueWithoutZeroWidthCharacters)) {
            $valueWithoutZeroWidthCharacters = $value;
        }

        return new self(\mb_strwidth(
            $valueWithoutZeroWidthCharacters,
            'UTF-8'
        ));
    }

    public static function sum(self ...$widths): self
    {
        return new self(\array_sum(\array_map(static function (Width $width): int {
            return $width->value;
        }, $widths)));
    }

    public static function max(
        self $width,
        self ...$widths
    ): self {
        $max = $width;

        foreach ($widths as $other) {
            if ($other->isGreaterThan($max)) {
                $max = $other;
            }
        }

        return $max;
    }

    /**
     * @throws Exception\InvalidWidth
     */
    public function minus(self $other): self
    {
        if ($other->isGreaterThan($this)) {
            throw Exception\InvalidWidth::subtrahendGreaterThanMinuend(
                $this,
                $other
            );
        }

        return new self($this->value - $other->value);
    }

    public function isGreaterThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
