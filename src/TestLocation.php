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
final class TestLocation
{
    private string $file;
    private int $line;

    private function __construct(
        string $file,
        int $line
    ) {
        $this->file = $file;
        $this->line = $line;
    }

    /**
     * @throws Exception\InvalidTestLocation
     */
    public static function create(
        string $file,
        int $line
    ): self {
        if ('' === \trim($file)) {
            throw Exception\InvalidTestLocation::fileBlankOrEmpty();
        }

        if (0 >= $line) {
            throw Exception\InvalidTestLocation::lineNotGreaterThanZero($line);
        }

        return new self(
            $file,
            $line,
        );
    }

    public function file(): string
    {
        return $this->file;
    }

    public function line(): int
    {
        return $this->line;
    }
}
