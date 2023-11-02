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
use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Count::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidCount::class)]
final class CountTest extends Framework\TestCase
{
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'lessThanZero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    public function testFromIntRejectsInvalidValue(int $value): void
    {
        $this->expectException(Exception\InvalidCount::class);

        Count::fromInt($value);
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testFromIntReturnsCount(int $value): void
    {
        $count = Count::fromInt($value);

        self::assertSame($value, $count->toInt());
    }
}
