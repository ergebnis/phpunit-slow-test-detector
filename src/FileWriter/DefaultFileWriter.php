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

namespace Ergebnis\PHPUnit\SlowTestDetector\FileWriter;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;

/**
 * @internal
 */
final class DefaultFileWriter implements FileWriter
{
    private File $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function write(string $contents): void
    {
        $out = $this->file->toString();

        \set_error_handler(static function (): void {
        });

        try {
            if (0 !== \strpos($out, 'php://')) {
                $this->createDirectory($out);
            }

            if (false === \file_put_contents($out, $contents)) {
                throw Exception\FailedToWriteFile::forFile($out);
            }
        } finally {
            \restore_error_handler();
        }
    }

    private function createDirectory(string $out): void
    {
        $directory = \dirname($out);

        if (!\is_dir($directory) && !\mkdir($directory, 0777, true) && !\is_dir($directory)) {
            throw Exception\FailedToCreateDirectory::forDirectory($directory);
        }
    }
}
