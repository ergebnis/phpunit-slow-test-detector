<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2024 Andreas Möller
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
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidSeconds
 */
final class InvalidSecondsTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNotGreaterThanZeroReturnsException(): void
    {
        $value = self::faker()->numberBetween();

        $exception = Exception\InvalidSeconds::notGreaterThanZero($value);

        $message = \sprintf(
            'Value should be greater than 0, but %d is not.',
            $value,
        );

        self::assertSame($message, $exception->getMessage());
    }

    public function testNotGreaterThanOrEqualToZeroReturnsException(): void
    {
        $value = self::faker()->numberBetween();

        $exception = Exception\InvalidSeconds::notGreaterThanOrEqualToZero($value);

        $message = \sprintf(
            'Value should be greater than or equal to 0, but %d is not.',
            $value,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
