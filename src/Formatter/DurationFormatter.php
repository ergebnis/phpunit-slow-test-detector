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

namespace Ergebnis\PHPUnit\SlowTestDetector\Formatter;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;

/**
 * @internal
 */
interface DurationFormatter
{
    public function format(Duration $duration): string;
}
