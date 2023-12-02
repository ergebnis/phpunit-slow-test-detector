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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Version;

use Ergebnis\DataProvider;
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
     * @dataProvider \Ergebnis\DataProvider\IntProvider::lessThanZero
     */
    public function testFromIntRejectsInvalidValue(int $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Value "%d" does not appear to be a valid value for a major version.',
            $value,
        ));

        Version\Major::fromInt($value);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testFromStringReturnsMajor(int $value): void
    {
        $major = Version\Major::fromInt($value);

        self::assertSame($value, $major->toInt());
    }

    public function testEqualsReturnsFalseWhenValueIsDifferent(): void
    {
        $faker = self::faker()->unique();

        $one = Version\Major::fromInt($faker->numberBetween(0));
        $two = Version\Major::fromInt($faker->numberBetween(0));

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsSame(): void
    {
        $value = self::faker()->numberBetween(0);

        $one = Version\Major::fromInt($value);
        $two = Version\Major::fromInt($value);

        self::assertTrue($one->equals($two));
    }
}
