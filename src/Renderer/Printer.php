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
 */
final class Printer
{
    /**
     * @var resource
     */
    private $output;

    /**
     * @param resource $output
     */
    public function __construct($output)
    {
        $this->output = $output;
    }

    public function print(string $text): void
    {
        if ('' === $text) {
            return;
        }

        \fwrite(
            $this->output,
            $text,
        );
    }
}
