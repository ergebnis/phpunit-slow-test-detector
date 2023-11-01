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

namespace Ergebnis\PHPUnit\SlowTestDetector\Exception;

/**
 * @internal
 */
final class InvalidNanoseconds extends \InvalidArgumentException
{
    public static function notGreaterThanZero(int $value): self
    {
        return new self(\sprintf(
            'Value should be greater than 0, but %d is not.',
            $value,
        ));
    }

    public static function notLessThanOrEqualTo999999999(int $value): self
    {
        return new self(\sprintf(
            'Value should be less than or equal to 999999999, but %d is not.',
            $value,
        ));
    }
}
