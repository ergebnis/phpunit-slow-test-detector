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
use Ergebnis\PHPUnit\SlowTestDetector\Width;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console\Terminal
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console\TerminalWidth
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Width
 */
final class TerminalTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testWidthReturnsTerminalWidthGreaterThanZero()
    {
        $terminalWidth = Reporter\Console\Terminal::width();

        self::assertTrue($terminalWidth->toWidth()->isGreaterThan(Width::fromInt(0)));
    }
}
