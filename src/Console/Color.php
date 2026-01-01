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

namespace Ergebnis\PHPUnit\SlowTestDetector\Console;

/**
 * @internal
 */
final class Color
{
    /**
     * @see https://github.com/sebastianbergmann/phpunit/blob/8.0.0/src/Util/Color.php#L109-L116
     */
    public static function dim(string $output): string
    {
        if (\trim($output) === '') {
            return $output;
        }

        return <<<TXT
\e[2m{$output}\e[22m
TXT;
    }
}
