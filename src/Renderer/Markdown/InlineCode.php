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

namespace Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown;

/**
 * @internal
 */
final class InlineCode
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /**
     * @see https://spec.commonmark.org/0.31.2/#code-spans
     */
    public function toString(): string
    {
        $longestBacktickRun = 0;

        if (0 < \preg_match_all('/`+/', $this->value, $matches)) {
            foreach ($matches[0] as $backtickRun) {
                $length = \strlen($backtickRun);

                if ($length > $longestBacktickRun) {
                    $longestBacktickRun = $length;
                }
            }
        }

        $fence = \str_repeat(
            '`',
            $longestBacktickRun + 1,
        );

        $value = $this->value;

        if (self::needsPadding($value)) {
            $value = ' ' . $value . ' ';
        }

        return $fence . $value . $fence;
    }

    private static function needsPadding(string $value): bool
    {
        if ('' === $value) {
            return false;
        }

        if (
            '`' === \substr($value, 0, 1)
            || '`' === \substr($value, -1)
        ) {
            return true;
        }

        if (
            ' ' === \substr($value, 0, 1)
            && ' ' === \substr($value, -1)
        ) {
            return true;
        }

        return false;
    }
}
