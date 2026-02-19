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
 *
 * @see https://en.wikipedia.org/wiki/ISO_8601#Times
 */
final class DefaultDurationFormatter implements DurationFormatter
{
    /**
     * @see https://github.com/sebastianbergmann/php-timer/blob/4.0.0/src/Duration.php
     */
    public function format(Duration $duration): string
    {
        $durationInMilliseconds = $duration->seconds() * 1000 + $duration->nanoseconds() / 1000000;

        $hours = (int) \floor($durationInMilliseconds / 60 / 60 / 1000);
        $hoursInMilliseconds = $hours * 60 * 60 * 1000;

        $minutes = ((int) \floor($durationInMilliseconds / 60 / 1000)) % 60;
        $minutesInMilliseconds = $minutes * 60 * 1000;

        $seconds = (int) \floor(($durationInMilliseconds - $hoursInMilliseconds - $minutesInMilliseconds) / 1000);
        $secondsInMilliseconds = $seconds * 1000;

        $milliseconds = (int) ($durationInMilliseconds - $hoursInMilliseconds - $minutesInMilliseconds - $secondsInMilliseconds);

        if (0 < $hours) {
            return \sprintf(
                '%02d:%02d:%02d.%03d',
                $hours,
                $minutes,
                $seconds,
                $milliseconds
            );
        }

        return \sprintf(
            '%02d:%02d.%03d',
            $minutes,
            $seconds,
            $milliseconds
        );
    }
}
