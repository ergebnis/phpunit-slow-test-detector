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

use Ergebnis\PHPUnit\SlowTestDetector\Exception\MaximumNumberNotGreaterThanZero;
use Ergebnis\Test\Util;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\MaximumNumberNotGreaterThanZero
 */
final class MaximumNumberNotGreaterThanZeroTest extends Framework\TestCase
{
    use Util\Helper;

    public function testCreareReturnsException(): void
    {
        $value = self::faker()->numberBetween();

        $exception = MaximumNumberNotGreaterThanZero::create($value);

        $message = \sprintf(
            'Maximum number should be greater than 0, but %d is not.',
            $value
        );

        self::assertSame($message, $exception->getMessage());
    }
}
