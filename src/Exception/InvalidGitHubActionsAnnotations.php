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
final class InvalidGitHubActionsAnnotations extends \InvalidArgumentException
{
    public static function notOneOf(
        string $value,
        string ...$allowedValues
    ): self {
        return new self(\sprintf(
            'Value should be one of "%s", but "%s" is not.',
            \implode(
                '", "',
                $allowedValues,
            ),
            $value,
        ));
    }
}
