<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\MaximumCount
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidMaximumCount
 */
final class MaximumCountTest extends Framework\TestCase
{
    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::lessThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testFromIntRejectsInvalidValue(int $value): void
    {
        $this->expectException(Exception\InvalidMaximumCount::class);

        MaximumCount::fromInt($value);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     */
    public function testFromSecondsReturnsMaximumDuration(int $value): void
    {
        $maximumCount = MaximumCount::fromInt($value);

        self::assertSame($value, $maximumCount->toInt());
    }
}
