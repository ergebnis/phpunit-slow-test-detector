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

#[Framework\Attributes\CoversClass(Exception\InvalidMilliseconds::class)]
final class InvalidMillisecondsTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNotGreaterThanOrEqualToZeroReturnsException(): void
    {
        $value = self::faker()->numberBetween();

        $exception = Exception\InvalidMilliseconds::notGreaterThanOrEqualToZero($value);

        $message = \sprintf(
            'Value should be greater than or equal to 0, but %d is not.',
            $value,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
