<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2023 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Console;

use Ergebnis\PHPUnit\SlowTestDetector\Console;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Console\Color
 */
final class ColorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty
     */
    public function testDimReturnsOriginalStringWhenItIsWhitespaceOnly(string $output): void
    {
        self::assertSame($output, Console\Color::dim($output));
    }

    public function testDimReturnsDimmedStringWhenItIsNotWhitespaceOnly(): void
    {
        $output = self::faker()->sentence();

        $expected = <<<TXT
\e[2m{$output}\e[22m
TXT;
        self::assertSame($expected, Console\Color::dim($output));
    }
}
