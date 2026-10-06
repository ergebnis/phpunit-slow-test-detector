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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Renderer;

use Ergebnis\PHPUnit\SlowTestDetector\Renderer;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Printer
 */
final class PrinterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testPrintPrintsNothingWhenTextIsEmpty(): void
    {
        $output = self::output();

        $printer = new Renderer\Printer($output);

        $printer->print('');

        self::assertOutputIsIdenticalTo('', $output);
    }

    public function testPrintPrintsTextWhenTextIsNotEmpty(): void
    {
        $faker = self::faker();

        $one = $faker->sentence();
        $two = $faker->sentence();

        $output = self::output();

        $printer = new Renderer\Printer($output);

        $printer->print($one);
        $printer->print($two);

        self::assertOutputIsIdenticalTo($one . $two, $output);
    }
}
