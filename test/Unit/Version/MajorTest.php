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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Version;

use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\Version;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Version\Major
 */
final class MajorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::lessThanZero
     */
    public function testFromIntRejectsInvalidValue(int $value)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Value "%d" does not appear to be a valid value for a major version.',
            $value
        ));

        Version\Major::fromInt($value);
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::zero
     */
    public function testFromStringReturnsMajor(int $value)
    {
        $major = Version\Major::fromInt($value);

        self::assertSame($value, $major->toInt());
    }

    public function testEqualsReturnsFalseWhenValueIsDifferent()
    {
        $faker = self::faker()->unique();

        $one = Version\Major::fromInt($faker->numberBetween(0));
        $two = Version\Major::fromInt($faker->numberBetween(0));

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsSame()
    {
        $value = self::faker()->numberBetween(0);

        $one = Version\Major::fromInt($value);
        $two = Version\Major::fromInt($value);

        self::assertTrue($one->equals($two));
    }

    public function testIsLessThanReturnsFalseWhenValueIsSame()
    {
        $value = self::faker()->numberBetween(0);

        $one = Version\Major::fromInt($value);
        $two = Version\Major::fromInt($value);

        self::assertFalse($one->isLessThan($two));
    }

    public function testIsLessThanReturnsFalseWhenValueIsGreater()
    {
        $value = self::faker()->numberBetween(0);

        $one = Version\Major::fromInt($value + 1);
        $two = Version\Major::fromInt($value);

        self::assertFalse($one->isLessThan($two));
    }

    public function testIsLessThanReturnsTrueWhenValueIsLess()
    {
        $value = self::faker()->numberBetween(0);

        $one = Version\Major::fromInt($value);
        $two = Version\Major::fromInt($value + 1);

        self::assertTrue($one->isLessThan($two));
    }

    public function testIsOneOfReturnsFalseWhenAllValuesAreDifferent()
    {
        $faker = self::faker()->unique();

        $one = Version\Major::fromInt($faker->numberBetween(0));
        $two = Version\Major::fromInt($faker->numberBetween(0));
        $three = Version\Major::fromInt($faker->numberBetween(0));

        self::assertFalse($one->isOneOf($two, $three));
    }

    public function testIsOneOfReturnsTrueWhenOneOfTheValuesIsSame()
    {
        $faker = self::faker()->unique();

        $value = $faker->numberBetween(0);

        $one = Version\Major::fromInt($value);
        $two = Version\Major::fromInt($faker->numberBetween(0));
        $three = Version\Major::fromInt($value);

        self::assertTrue($one->isOneOf($two, $three));
    }
}
