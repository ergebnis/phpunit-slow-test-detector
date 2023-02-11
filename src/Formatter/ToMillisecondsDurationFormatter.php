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

namespace Ergebnis\PHPUnit\SlowTestDetector\Formatter;

use PHPUnit\Event;

/**
 * @internal
 *
 * @psalm-immutable
 */
final class ToMillisecondsDurationFormatter implements DurationFormatter
{
    public function format(Event\Telemetry\Duration $duration): string
    {
        $milliseconds = $duration->seconds() * (10 ** 3) + (int) \round($duration->nanoseconds() / (10 ** 6));

        return \sprintf(
            '%s ms',
            \number_format($milliseconds),
        );
    }
}
