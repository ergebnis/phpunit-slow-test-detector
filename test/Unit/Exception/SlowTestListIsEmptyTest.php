<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2025 Andreas Möller
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
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\SlowTestListIsEmpty
 */
final class SlowTestListIsEmptyTest extends Framework\TestCase
{
    public function testFromPhaseIdentifierReturnsException()
    {
        $exception = Exception\SlowTestListIsEmpty::create();

        $message = 'Slow test list is empty.';

        self::assertSame($message, $exception->getMessage());
    }
}
