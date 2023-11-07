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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\DataProvider;
use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Seconds;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Seconds::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidSeconds::class)]
final class SecondsTest extends Framework\TestCase
{
    #[Framework\Attributes\DataProviderExternal(DataProvider\FloatProvider::class, 'lessThanZero')]
    public function testFromFloatRejectsValueLessThanZero(float $value): void
    {
        $this->expectException(Exception\InvalidSeconds::class);

        Seconds::fromFloat($value);
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\FloatProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\FloatProvider::class, 'greaterThanZero')]
    public function testFromFloatReturnsSecondsWhereValueIsIntegerPartOfFloatValue(float $value): void
    {
        $seconds = Seconds::fromFloat($value);

        $expected = (int) \floor($value);

        self::assertSame($expected, $seconds->toInt());
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'lessThanZero')]
    public function testFromIntRejectsValueLessThanZero(int $value): void
    {
        $this->expectException(Exception\InvalidSeconds::class);

        Seconds::fromInt($value);
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testFromIntReturnsSeconds(int $value): void
    {
        $seconds = Seconds::fromInt($value);

        self::assertSame($value, $seconds->toInt());
    }
}
