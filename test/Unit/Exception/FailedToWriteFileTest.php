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
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\FailedToWriteFile
 */
final class FailedToWriteFileTest extends Framework\TestCase
{
    public function testForFileReturnsException(): void
    {
        $exception = Exception\FailedToWriteFile::forFile('/foo/bar/baz.xml');

        self::assertSame('Failed to write to file "/foo/bar/baz.xml".', $exception->getMessage());
    }
}
