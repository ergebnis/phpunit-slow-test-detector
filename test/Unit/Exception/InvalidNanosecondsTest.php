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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Exception;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidNanoseconds
 */
final class InvalidNanosecondsTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNotGreaterThanOrEqualToZeroReturnsException(): void
    {
        $value = self::faker()->numberBetween();

        $exception = Exception\InvalidNanoseconds::notGreaterThanOrEqualToZero($value);

        $message = \sprintf(
            'Value should be greater than or equal to 0, but %d is not.',
            $value,
        );

        self::assertSame($message, $exception->getMessage());
    }

    public function testNotLessThanOrEqualToReturnsException(): void
    {
        $faker = self::faker();

        $one = $faker->numberBetween();
        $two = $faker->numberBetween();

        $exception = Exception\InvalidNanoseconds::notLessThanOrEqualTo(
            $one,
            $two,
        );

        $message = \sprintf(
            'Value should be less than or equal to %d, but %d is not.',
            $two,
            $one,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
