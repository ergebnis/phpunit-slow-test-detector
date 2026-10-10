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
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidTestLocation
 */
final class InvalidTestLocationTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFileBlankOrEmptyReturnsInvalidTestLocation(): void
    {
        $exception = Exception\InvalidTestLocation::fileBlankOrEmpty();

        self::assertSame('File cannot be blank or empty.', $exception->getMessage());
    }

    public function testLineNotGreaterThanZeroReturnsInvalidTestLocation(): void
    {
        $value = self::faker()->numberBetween(-100, 0);

        $exception = Exception\InvalidTestLocation::lineNotGreaterThanZero($value);

        $message = \sprintf(
            'Line should be greater than 0, but %d is not.',
            $value,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
