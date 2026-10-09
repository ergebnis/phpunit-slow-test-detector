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
    private File $file;
    private Line $line;

    private function __construct(
        File $file,
        Line $line
    ) {
        $this->file = $file;
        $this->line = $line;
    }

    public static function create(
        File $file,
        Line $line
    ): self {
        return new self(
            $file,
            $line,
        );
    }

    public function file(): File
    {
        return $this->file;
    }

    public function line(): Line
    {
        return $this->line;
    }
}
