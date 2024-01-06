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

namespace Ergebnis\PHPUnit\SlowTestDetector\Exception;

/**
 * @internal
 */
final class FailedToWriteFile extends \RuntimeException
{
    public static function forFile(string $file): self
    {
        return new self(\sprintf('Failed to write to file "%s".', $file));
    }
}
