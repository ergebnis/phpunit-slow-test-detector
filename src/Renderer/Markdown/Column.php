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
final class Column
{
    private string $heading;
    private Alignment $alignment;

    private function __construct(
        string $heading,
        Alignment $alignment
    ) {
        $this->heading = $heading;
        $this->alignment = $alignment;
    }

    public static function create(
        string $heading,
        Alignment $alignment
    ): self {
        return new self(
            $heading,
            $alignment,
        );
    }

    public function heading(): string
    {
        return $this->heading;
    }

    public function alignment(): Alignment
    {
        return $this->alignment;
    }
}
