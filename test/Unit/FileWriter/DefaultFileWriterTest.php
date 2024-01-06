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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\FileWriter;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\FileWriter;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\FileWriter\DefaultFileWriter
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\FileWriter\File
 */
final class DefaultFileWriterTest extends Framework\TestCase
{
    private string $tempDir;
    private string $tempFile;

    protected function setUp(): void
    {
        $this->tempDir = \sys_get_temp_dir() . '/slow-tests/foo';
        $this->tempFile = $this->tempDir . '/bar.xml';
    }

    protected function tearDown(): void
    {
        if (\is_file($this->tempFile)) {
            \unlink($this->tempFile);
        }

        if (\is_dir($this->tempDir)) {
            \rmdir($this->tempDir);
        }

        if (\is_dir($rootDir = \dirname($this->tempDir))) {
            \rmdir($rootDir);
        }
    }

    public function testContentsIsWrittenSuccessfullyToFile(): void
    {
        $fileWriter = new FileWriter\DefaultFileWriter(
            FileWriter\File::fromString($this->tempFile),
        );

        $fileWriter->write('foo');

        self::assertFileExists($this->tempFile);
        self::assertStringEqualsFile($this->tempFile, 'foo');
    }

    public function testExceptionIsThrownWhenDirectoryCannotBeCreated(): void
    {
        \mkdir(\dirname($this->tempDir), 000);

        $fileWriter = new FileWriter\DefaultFileWriter(
            FileWriter\File::fromString($this->tempFile),
        );

        $this->expectException(Exception\FailedToCreateDirectory::class);
        $this->expectExceptionMessage(\sprintf('Directory "%s" was not created.', $this->tempDir));

        $fileWriter->write('foo');
    }

    public function testExceptionIsThrownWhenFileCannotBeWritten(): void
    {
        \mkdir($this->tempDir, 0777, true);
        \touch($this->tempFile);
        \chmod($this->tempFile, 000);

        $fileWriter = new FileWriter\DefaultFileWriter(
            FileWriter\File::fromString($this->tempFile),
        );

        $this->expectException(Exception\FailedToWriteFile::class);
        $this->expectExceptionMessage(\sprintf('Failed to write to file "%s".', $this->tempFile));

        $fileWriter->write('foo');
    }
}
