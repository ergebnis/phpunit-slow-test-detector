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

use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestCount;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\SlowTestCount
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Count
 */
final class SlowTestCountTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::zero
     */
    public function testFromCountReturnsMaximumCount(int $value)
    {
        $count = Count::fromInt($value);

        $slowTestCount = SlowTestCount::fromCount($count);

        self::assertSame($count, $slowTestCount->toCount());
    }

    public function testEqualsReturnsFalseWhenValueIsDifferent()
    {
        $faker = self::faker()->unique();

        $one = SlowTestCount::fromCount(Count::fromInt($faker->numberBetween(0)));
        $two = SlowTestCount::fromCount(Count::fromInt($faker->numberBetween(0)));

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsFalseWhenValueIsSame()
    {
        $count = Count::fromInt(self::faker()->numberBetween(0));

        $one = SlowTestCount::fromCount($count);
        $two = SlowTestCount::fromCount($count);

        self::assertTrue($one->equals($two));
    }
}
