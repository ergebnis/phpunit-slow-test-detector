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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumWidth;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\Width;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\MaximumWidth
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidMaximumWidth
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Width
 */
final class MaximumWidthTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testMinimumReturnsMaximumWidth()
    {
        $maximumWidth = MaximumWidth::minimum();

        self::assertEquals(Width::fromInt(80), $maximumWidth->toWidth());
        self::assertFalse($maximumWidth->isUnlimited());
    }

    public function testUnlimitedReturnsMaximumWidth()
    {
        $maximumWidth = MaximumWidth::unlimited();

        self::assertEquals(Width::fromInt(\PHP_INT_MAX), $maximumWidth->toWidth());
        self::assertTrue($maximumWidth->isUnlimited());
    }

    public function testFromWidthRejectsWidthLessThanMinimum()
    {
        $width = Width::fromInt(self::faker()->numberBetween(0, 79));

        $this->expectException(Exception\InvalidMaximumWidth::class);

        MaximumWidth::fromWidth($width);
    }

    public function testFromWidthReturnsMaximumWidthWhenWidthIsMinimum()
    {
        $width = Width::fromInt(80);

        $maximumWidth = MaximumWidth::fromWidth($width);

        self::assertSame($width, $maximumWidth->toWidth());
        self::assertFalse($maximumWidth->isUnlimited());
    }

    public function testFromWidthReturnsMaximumWidthWhenWidthIsGreaterThanMinimum()
    {
        $width = Width::fromInt(self::faker()->numberBetween(81));

        $maximumWidth = MaximumWidth::fromWidth($width);

        self::assertSame($width, $maximumWidth->toWidth());
        self::assertFalse($maximumWidth->isUnlimited());
    }
}
