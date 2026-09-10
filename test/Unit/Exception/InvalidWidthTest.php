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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Exception;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\Width;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidWidth
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Width
 */
final class InvalidWidthTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNotGreaterThanZeroReturnsException()
    {
        $value = self::faker()->numberBetween();

        $exception = Exception\InvalidWidth::notGreaterThanOrEqualToZero($value);

        $message = \sprintf(
            'Value should be greater than or equal to 0, but %d is not.',
            $value
        );

        self::assertSame($message, $exception->getMessage());
    }

    public function testSubtrahendGreaterThanMinuendReturnsException()
    {
        $faker = self::faker();

        $minuend = Width::fromInt($faker->numberBetween(0, 9));
        $subtrahend = Width::fromInt($faker->numberBetween(10, 20));

        $exception = Exception\InvalidWidth::subtrahendGreaterThanMinuend(
            $minuend,
            $subtrahend
        );

        $message = \sprintf(
            'Width %d can not be subtracted from width %d, as the result would be negative.',
            $subtrahend->toInt(),
            $minuend->toInt()
        );

        self::assertSame($message, $exception->getMessage());
    }
}
