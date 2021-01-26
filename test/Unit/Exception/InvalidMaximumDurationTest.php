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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Exception;

use Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidMaximumDuration;
use Ergebnis\Test\Util;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidMaximumDuration
 */
final class InvalidMaximumDurationTest extends Framework\TestCase
{
    use Util\Helper;

    public function testNotGreaterThanReturnsException(): void
    {
        $value = self::faker()->numberBetween();

        $exception = InvalidMaximumDuration::notGreaterThanZero($value);

        $message = \sprintf(
            'Value should be greater than 0, but %d is not.',
            $value
        );

        self::assertSame($message, $exception->getMessage());
    }
}
