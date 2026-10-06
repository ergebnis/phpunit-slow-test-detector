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

namespace Ergebnis\PHPUnit\SlowTestDetector\Renderer;

/**
 * @internal
 *
 * @see https://github.com/sebastianbergmann/phpunit/blob/13.4.0/src/TextUI/Output/Compact/Renderer.php
 * @see https://github.com/sebastianbergmann/phpunit/blob/13.4.0/src/TextUI/Output/Compact/ResultPrinter.php
 */
final class CompactRenderer
{
    private Sanitizer $sanitizer;

    public function __construct(Sanitizer $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

    /**
     * Renders the banner that starts a section ("=== name ===").
     */
    public function banner(string $name): string
    {
        return \PHP_EOL . \sprintf(
            '=== %s ===',
            $this->singleLine($name),
        ) . \PHP_EOL;
    }

    /**
     * Renders the header line that starts a record ("--- TYPE: title").
     *
     * The header is always exactly one line, so that a title cannot start a record of its own.
     */
    public function header(
        string $type,
        string $title
    ): string {
        return \PHP_EOL . \sprintf(
            '--- %s: %s',
            $type,
            $this->singleLine($title),
        ) . \PHP_EOL;
    }

    /**
     * Renders one or more lines of text that belong to the current record.
     */
    public function body(string $body): string
    {
        return $this->sanitizer->sanitize($body) . \PHP_EOL;
    }

    /**
     * Renders the summary line that ends a section ("STATUS (count, count)").
     *
     * @param list<string> $counts
     */
    public function summary(
        string $status,
        array $counts
    ): string {
        return \PHP_EOL . \sprintf(
            '%s (%s)',
            $status,
            \implode(
                ', ',
                $counts,
            ),
        ) . \PHP_EOL;
    }

    private function singleLine(string $text): string
    {
        return \str_replace(
            [
                "\r",
                "\n",
            ],
            [
                '\u{000D}',
                '\u{000A}',
            ],
            $this->sanitizer->sanitize($text),
        );
    }
}
