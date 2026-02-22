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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Reporter\Console;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console\DefaultDurationFormatter
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console\Unit
 */
final class DefaultDurationFormatterTest extends Framework\TestCase
{
    /**
     * @dataProvider provideDurationUnitAndFormattedDuration
     */
    public function testFormatFormats(
        Reporter\Console\Unit $unit,
        Duration $duration,
        string $formattedDuration
    ) {
        $formatter = new Reporter\Console\DefaultDurationFormatter();

        $formatted = $formatter->format(
            $unit,
            $duration
        );

        self::assertSame($formattedDuration, $formatted);
    }

    /**
     * @return \Generator<string, array{0: \Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console\Unit, 1: Duration, 2: string}>
     */
    public static function provideDurationUnitAndFormattedDuration(): iterable
    {
        $values = [
            'seconds-zero' => [
                Reporter\Console\Unit::seconds(),
                Duration::fromSecondsAndNanoseconds(
                    0,
                    0
                ),
                '0.000',
            ],
            'seconds-milliseconds' => [
                Reporter\Console\Unit::seconds(),
                Duration::fromSecondsAndNanoseconds(
                    0,
                    123999000
                ),
                '0.123',
            ],
            'seconds-digits-one' => [
                Reporter\Console\Unit::seconds(),
                Duration::fromSecondsAndNanoseconds(
                    1,
                    234456789
                ),
                '1.234',
            ],
            'seconds-digits-two' => [
                Reporter\Console\Unit::seconds(),
                Duration::fromSecondsAndNanoseconds(
                    12,
                    345678912
                ),
                '12.345',
            ],
            'minutes-zero' => [
                Reporter\Console\Unit::minutes(),
                Duration::fromSecondsAndNanoseconds(
                    0,
                    0
                ),
                '0:00.000',
            ],
            'minutes-seconds-only' => [
                Reporter\Console\Unit::minutes(),
                Duration::fromSecondsAndNanoseconds(
                    12,
                    345678912
                ),
                '0:12.345',
            ],
            'minutes-digits-one' => [
                Reporter\Console\Unit::minutes(),
                Duration::fromSecondsAndNanoseconds(
                    1 * 60 + 23,
                    456789012
                ),
                '1:23.456',
            ],
            'minutes-digits-two' => [
                Reporter\Console\Unit::minutes(),
                Duration::fromSecondsAndNanoseconds(
                    12 * 60 + 34,
                    567890123
                ),
                '12:34.567',
            ],
            'hours-zero' => [
                Reporter\Console\Unit::hours(),
                Duration::fromSecondsAndNanoseconds(
                    0,
                    0
                ),
                '0:00:00.000',
            ],
            'hours-seconds-only' => [
                Reporter\Console\Unit::hours(),
                Duration::fromSecondsAndNanoseconds(
                    12,
                    345678912
                ),
                '0:00:12.345',
            ],
            'hours-digits-one' => [
                Reporter\Console\Unit::hours(),
                Duration::fromSecondsAndNanoseconds(
                    60 * 60 + 23 * 60 + 45,
                    567890123
                ),
                '1:23:45.567',
            ],
            'hours-digits-two' => [
                Reporter\Console\Unit::hours(),
                Duration::fromSecondsAndNanoseconds(
                    12 * 60 * 60 + 34 * 60 + 56,
                    789012345
                ),
                '12:34:56.789',
            ],
            'hours-digits-two-nanoseconds-zero' => [
                Reporter\Console\Unit::hours(),
                Duration::fromSecondsAndNanoseconds(
                    12 * 60 * 60 + 34 * 60 + 56,
                    00
                ),
                '12:34:56.000',
            ],
            'hours-digits-two-seconds-zero' => [
                Reporter\Console\Unit::hours(),
                Duration::fromSecondsAndNanoseconds(
                    12 * 60 * 60 + 34 * 60,
                    00
                ),
                '12:34:00.000',
            ],
        ];

        foreach ($values as $key => list($unit, $duration, $formattedDuration)) {
            yield $key => [
                $unit,
                $duration,
                $formattedDuration,
            ];
        }
    }
}
