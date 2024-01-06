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
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidTestFileLine
 */
final class InvalidTestFileLineTest extends Framework\TestCase
{
    public function testLesserThenOneReturnsException(): void
    {
        $exception = Exception\InvalidTestFileLine::lesserThenOne();

        self::assertSame('Value cannot be lesser than one.', $exception->getMessage());
    }
}
