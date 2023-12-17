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
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidPhaseIdentifier
 */
final class InvalidPhaseIdentifierTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testBlankOrEmptyReturnsException(): void
    {
        $exception = Exception\InvalidPhaseIdentifier::blankOrEmpty();

        self::assertSame('Value can not be blank or empty.', $exception->getMessage());
    }
}
