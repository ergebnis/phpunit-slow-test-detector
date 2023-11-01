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

namespace Ergebnis\PHPUnit\SlowTestDetector\Comparator;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;

/**
 * @internal
 */
final class DurationComparator
{
    public function compare(
        Duration $one,
        Duration $two,
    ): int {
        if ($one->isLessThan($two)) {
            return -1;
        }

        if ($one->isGreaterThan($two)) {
            return 1;
        }

        return 0;
    }
}
