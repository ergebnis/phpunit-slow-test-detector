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
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidMaximumWidth
 */
final class InvalidMaximumWidthTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testLessThanMinimumReturnsException()
    {
        $faker = self::faker();

        $value = $faker->numberBetween(0, 79);
        $minimum = $faker->numberBetween(80);

        $exception = Exception\InvalidMaximumWidth::lessThanMinimum(
            $value,
            $minimum
        );

        $message = \sprintf(
            'Value should be greater than or equal to %d, but %d is not.',
            $minimum,
            $value
        );

        self::assertSame($message, $exception->getMessage());
    }
}
