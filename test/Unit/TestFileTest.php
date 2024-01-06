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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\DataProvider;
use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestFile;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\TestFile
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidTestFileLine
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidTestFilename
 */
final class TestFileTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty
     */
    public function testFromFilenameRejectsInvalidFilename(string $value): void
    {
        $this->expectException(Exception\InvalidTestFilename::class);

        TestFile::fromFilename($value);
    }

    public function testFromFilenameReturnsTestFile(): void
    {
        $value = self::faker()->word();

        $testFile = TestFile::fromFilename($value);

        self::assertSame($value, $testFile->filename());
        self::assertNull($testFile->line());
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty
     */
    public function testFromFilenameAndLineRejectsInvalidFilename(string $value): void
    {
        $this->expectException(Exception\InvalidTestFilename::class);

        TestFile::fromFilenameAndLine($value, 1);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::lessThanZero()
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero()
     */
    public function testFromFilenameAndLineRejectsInvalidLine(int $value): void
    {
        $this->expectException(Exception\InvalidTestFileLine::class);

        TestFile::fromFilenameAndLine('foo', $value);
    }

    public function testFromFilenameAndLineReturnsTestFile(): void
    {
        $filename = self::faker()->word();
        $line = self::faker()->numberBetween(1, 1000);

        $testFile = TestFile::fromFilenameAndLine($filename, $line);

        self::assertSame($filename, $testFile->filename());
        self::assertSame($line, $testFile->line());
    }
}
