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

namespace Ergebnis\PHPUnit\SlowTestDetector\Reporter\Formatter;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;

/**
 * @internal
 */
final class DefaultDurationFormatter implements DurationFormatter
{
    public function format(
        Unit $unit,
        Duration $duration
    ): string {
        $durationInMilliseconds = $duration->seconds() * 1000 + $duration->nanoseconds() / 1000000;

        $hours = (int) \floor($durationInMilliseconds / 60 / 60 / 1000);
        $hoursInMilliseconds = $hours * 60 * 60 * 1000;

        $minutes = ((int) \floor($durationInMilliseconds / 60 / 1000)) % 60;
        $minutesInMilliseconds = $minutes * 60 * 1000;

        $seconds = (int) \floor(($durationInMilliseconds - $hoursInMilliseconds - $minutesInMilliseconds) / 1000);
        $secondsInMilliseconds = $seconds * 1000;

        $milliseconds = (int) ($durationInMilliseconds - $hoursInMilliseconds - $minutesInMilliseconds - $secondsInMilliseconds);

        if ($unit->equals(Unit::hours())) {
            return \sprintf(
                '%d:%02d:%02d.%03d',
                $hours,
                $minutes,
                $seconds,
                $milliseconds
            );
        }

        if ($unit->equals(Unit::minutes())) {
            return \sprintf(
                '%d:%02d.%03d',
                $minutes + $hours * 60,
                $seconds,
                $milliseconds
            );
        }

        return \sprintf(
            '%d.%03d',
            $seconds + $minutes * 60 + $hours * 3600,
            $milliseconds
        );
    }
}
