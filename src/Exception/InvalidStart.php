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

namespace Ergebnis\PHPUnit\SlowTestDetector\Exception;

/**
 * @internal
 */
final class InvalidStart extends \InvalidArgumentException
{
    public static function notLessThanOrEqualToEnd(): self
    {
        return new self('Start should be less than or equal to end.');
    }
}
