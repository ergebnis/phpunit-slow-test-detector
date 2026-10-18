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
 *
 * @see https://github.github.com/gfm/#tables-extension-
 */
final class Alignment
{
    private string $delimiter;

    private function __construct(string $delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public static function left(): self
    {
        return new self(':--');
    }

    public static function right(): self
    {
        return new self('--:');
    }

    public static function center(): self
    {
        return new self(':-:');
    }

    public function delimiter(): string
    {
        return $this->delimiter;
    }
}
