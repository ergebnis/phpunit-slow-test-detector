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
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\Width;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Width
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidWidth
 */
final class WidthTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::lessThanZero
     */
    public function testFromIntRejectsInvalidValue(int $value)
    {
        $this->expectException(Exception\InvalidWidth::class);

        Width::fromInt($value);
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::zero
     */
    public function testFromIntReturnsWidth(int $value)
    {
        $width = Width::fromInt($value);

        self::assertSame($value, $width->toInt());
    }

    public function testFromStringReturnsWidthWhenValueIsEmpty()
    {
        $width = Width::fromString('');

        self::assertSame(0, $width->toInt());
    }

    public function testFromStringReturnsWidthWhenValueIsSingleByte()
    {
        $value = self::faker()->word();

        $width = Width::fromString($value);

        self::assertSame(\strlen($value), $width->toInt());
    }

    public function testFromStringReturnsWidthWhenValueIsMultibyte()
    {
        $width = Width::fromString('ÄÖÜ');

        self::assertSame(3, $width->toInt());
    }

    public function testFromStringReturnsWidthWhenValueContainsWideCharacters()
    {
        $width = Width::fromString('日本語');

        self::assertSame(6, $width->toInt());
    }

    public function testFromStringReturnsWidthWhenValueContainsZeroWidthCharacters()
    {
        $width = Width::fromString("e\xCC\x81");

        self::assertSame(1, $width->toInt());
    }

    public function testSumReturnsWidthWhenWidthsAreEmpty()
    {
        $sum = Width::sum();

        self::assertEquals(Width::fromInt(0), $sum);
    }

    public function testSumReturnsWidthWhenWidthsAreNotEmpty()
    {
        $faker = self::faker();

        $widths = \array_map(static function () use ($faker): Width {
            return Width::fromString($faker->word());
        }, \range(0, 2));

        $sum = Width::sum(...$widths);

        $expected = Width::fromInt(\array_sum(\array_map(static function (Width $width): int {
            return $width->toInt();
        }, $widths)));

        self::assertEquals($expected, $sum);
    }

    public function testMaxReturnsWidthWhenOnlyOneWidthIsProvided()
    {
        $width = Width::fromInt(self::faker()->numberBetween(0));

        $max = Width::max($width);

        self::assertEquals($width, $max);
    }

    public function testMaxReturnsGreatestWidthWhenMoreThanOneWidthIsProvided()
    {
        $faker = self::faker();

        $one = Width::fromInt($faker->numberBetween(0, 9));
        $two = Width::fromInt($faker->numberBetween(20, 29));
        $three = Width::fromInt($faker->numberBetween(10, 19));

        $width = Width::max(
            $one,
            $two,
            $three
        );

        self::assertEquals($two, $width);
    }

    public function testMinusThrowsInvalidWidthWhenOtherIsGreater()
    {
        $faker = self::faker();

        $one = Width::fromInt($faker->numberBetween(0, 9));
        $two = Width::fromInt($faker->numberBetween(10, 20));

        $this->expectException(Exception\InvalidWidth::class);

        $one->minus($two);
    }

    public function testMinusReturnsWidthWhenOtherIsNotGreater()
    {
        $faker = self::faker();

        $one = $faker->numberBetween(10, 20);
        $two = $faker->numberBetween(0, 10);

        $width = Width::fromInt($one)->minus(Width::fromInt($two));

        self::assertSame($one - $two, $width->toInt());
    }

    public function testIsGreaterThanReturnsFalseWhenValueIsLess()
    {
        $faker = self::faker();

        $one = Width::fromInt($faker->numberBetween(0, 9));
        $two = Width::fromInt($faker->numberBetween(10, 20));

        self::assertFalse($one->isGreaterThan($two));
    }

    public function testIsGreaterThanReturnsFalseWhenValuesAreSame()
    {
        $value = self::faker()->numberBetween(0);

        $one = Width::fromInt($value);
        $two = Width::fromInt($value);

        self::assertFalse($one->isGreaterThan($two));
    }

    public function testIsGreaterThanReturnsTrueWhenValueIsGreater()
    {
        $faker = self::faker();

        $one = Width::fromInt($faker->numberBetween(10, 20));
        $two = Width::fromInt($faker->numberBetween(0, 9));

        self::assertTrue($one->isGreaterThan($two));
    }
}
