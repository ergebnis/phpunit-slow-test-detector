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

namespace Ergebnis\PHPUnit\SlowTestDetector\Exception;

/**
 * @internal
 */
final class InvalidMaximumWidth extends \InvalidArgumentException
{
    public static function lessThanMinimum(
        int $value,
        int $minimum
    ): self {
        return new self(\sprintf(
            'Value should be greater than or equal to %d, but %d is not.',
            $minimum,
            $value
        ));
    }
}
