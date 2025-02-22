<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Count
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidCount
 */
final class CountTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::lessThanZero
     */
    public function testFromIntRejectsInvalidValue(int $value)
    {
        $this->expectException(Exception\InvalidCount::class);

        Count::fromInt($value);
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::zero
     */
    public function testFromIntReturnsCount(int $value)
    {
        $count = Count::fromInt($value);

        self::assertSame($value, $count->toInt());
    }

    public function testEqualsReturnsFalseWhenValueIsDifferent()
    {
        $faker = self::faker()->unique();

        $one = Count::fromInt($faker->numberBetween(0));
        $two = Count::fromInt($faker->numberBetween(0));

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsSame()
    {
        $value = self::faker()->numberBetween(0);

        $one = Count::fromInt($value);
        $two = Count::fromInt($value);

        self::assertTrue($one->equals($two));
    }
}
