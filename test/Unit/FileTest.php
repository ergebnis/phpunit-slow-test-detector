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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\File;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\File
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidFile
 */
final class FileTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::empty
     */
    public function testFromStringThrowsInvalidFileWhenValueIsBlankOrEmpty(string $value): void
    {
        $this->expectException(Exception\InvalidFile::class);

        File::fromString($value);
    }

    public function testFromStringReturnsFileWhenValueIsNotBlankOrEmpty(): void
    {
        $value = self::faker()->slug();

        $file = File::fromString($value);

        self::assertSame($value, $file->toString());
    }
}
