<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Console;

use Ergebnis\DataProvider;
use Ergebnis\PHPUnit\SlowTestDetector\Console;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Console\Color::class)]
final class ColorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'blank')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'empty')]
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
