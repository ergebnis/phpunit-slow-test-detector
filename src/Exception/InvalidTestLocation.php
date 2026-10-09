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
final class InvalidTestLocation extends \InvalidArgumentException
{
    public static function fileBlankOrEmpty(): self
    {
        return new self('File cannot be blank or empty.');
    }

    public static function lineNotGreaterThanZero(int $value): self
    {
        return new self(\sprintf(
            'Line should be greater than 0, but %d is not.',
            $value,
        ));
    }
}
