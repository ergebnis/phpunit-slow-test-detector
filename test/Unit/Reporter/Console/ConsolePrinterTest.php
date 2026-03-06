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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Reporter\Console;

use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console\ConsolePrinter
 */
final class ConsolePrinterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testPrintLineWritesLineFollowedByNewlineToResource()
    {
        $line = self::faker()->sentence();

        $output = \fopen(
            'php://memory',
            'rb+'
        );

        $printer = new Reporter\Console\ConsolePrinter($output);

        $printer->printLine($line);

        \rewind($output);

        $expectedOutput = \sprintf(
            "%s\n",
            $line
        );

        self::assertSame($expectedOutput, \stream_get_contents($output));
    }
}
