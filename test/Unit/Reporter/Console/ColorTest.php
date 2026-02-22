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
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console\Color
 */
final class ColorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::empty
     */
    public function testDimReturnsOriginalStringWhenItIsWhitespaceOnly(string $output)
    {
        self::assertSame($output, Reporter\Console\Color::dim($output));
    }

    public function testDimReturnsDimmedStringWhenItIsNotWhitespaceOnly()
    {
        $output = self::faker()->sentence();

        $expected = \sprintf(
            "\e[2m%s\e[22m",
            $output
        );

        self::assertSame($expected, Reporter\Console\Color::dim($output));
    }
}
