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

use Ergebnis\PHPUnit\SlowTestDetector\Width;

/**
 * @internal
 */
final class InvalidWidth extends \InvalidArgumentException
{
    public static function notGreaterThanOrEqualToZero(int $value): self
    {
        return new self(\sprintf(
            'Value should be greater than or equal to 0, but %d is not.',
            $value
        ));
    }

    public static function subtrahendGreaterThanMinuend(
        Width $minuend,
        Width $subtrahend
    ): self {
        return new self(\sprintf(
            'Width %d can not be subtracted from width %d, as the result would be negative.',
            $subtrahend->toInt(),
            $minuend->toInt()
        ));
    }
}
