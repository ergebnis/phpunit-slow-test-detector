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

final class InvalidMaximumCount extends \InvalidArgumentException
{
    public static function notGreaterThanZero(int $value): self
    {
        return new self(\sprintf(
            'Value should be greater than 0, but %d is not.',
            $value,
        ));
    }
}
