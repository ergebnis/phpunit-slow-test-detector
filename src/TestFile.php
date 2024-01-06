<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2024 Andreas Möller
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
final class TestFile
{
    private string $filename;
    private ?int $line;

    private function __construct(
        string $filename,
        ?int $line = null
    ) {
        $this->filename = $filename;
        $this->line = $line;
    }

    /**
     * @throws Exception\InvalidTestFilename
     */
    public static function fromFilename(string $filename): self
    {
        if ('' === \trim($filename)) {
            throw Exception\InvalidTestFilename::blankOrEmpty();
        }

        return new self($filename);
    }

    /**
     * @throws Exception\InvalidTestFileLine
     * @throws Exception\InvalidTestFilename
     */
    public static function fromFilenameAndLine(string $filename, int $line): self
    {
        if ('' === \trim($filename)) {
            throw Exception\InvalidTestFilename::blankOrEmpty();
        }

        if (1 > $line) {
            throw Exception\InvalidTestFileLine::lesserThenOne();
        }

        return new self($filename, $line);
    }

    public function filename(): string
    {
        return $this->filename;
    }

    public function line(): ?int
    {
        return $this->line;
    }
}
