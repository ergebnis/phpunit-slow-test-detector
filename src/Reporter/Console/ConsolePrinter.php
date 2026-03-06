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

namespace Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;

/**
 * @internal
 */
final class ConsolePrinter
{
    /**
     * @var resource
     */
    private $output;

    /**
     * @param resource $output
     *
     * @throws Exception\InvalidOutput
     */
    public function __construct($output)
    {
        if (!\is_resource($output)) {
            throw Exception\InvalidOutput::notResource($output);
        }

        $this->output = $output;
    }

    public function printLine(string $line)
    {
        \fwrite(
            $this->output,
            $line . "\n"
        );
    }
}
