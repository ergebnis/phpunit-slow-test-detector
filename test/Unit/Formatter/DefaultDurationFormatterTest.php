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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Formatter;

use Ergebnis\PHPUnit\SlowTestDetector\Formatter;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Event;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Formatter\DefaultDurationFormatter::class)]
final class DefaultDurationFormatterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProvider('provideDurationAndFormattedDuration')]
    public function testFormatFormats(
        Event\Telemetry\Duration $duration,
        string $formattedDuration,
    ): void {
        $formatter = new Formatter\DefaultDurationFormatter();

        self::assertSame($formattedDuration, $formatter->format($duration));
    }

    /**
     * @return array<string, array{0: Event\Telemetry\Duration, 1: string}>
     */
    public static function provideDurationAndFormattedDuration(): array
    {
        return [
            'zero' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    0,
                ),
                '0.000',
            ],
            'milliseconds' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    123_999_000,
                ),
                '0.123',
            ],
            'seconds-digits-one' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    1,
                    234_456_789,
                ),
                '1.234',
            ],
            'seconds-digits-two' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    12,
                    345_678_912,
                ),
                '12.345',
            ],
            'minutes-digits-one' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    1 * 60 + 23,
                    456_789_012,
                ),
                '1:23.456',
            ],
            'minutes-digits-two' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    12 * 60 + 34,
                    567_890_123,
                ),
                '12:34.567',
            ],
            'hours-digits-one' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    60 * 60 + 23 * 60 + 45,
                    567_890_123,
                ),
                '1:23:45.567',
            ],
            'hours-digits-two' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    12 * 60 * 60 + 34 * 60 + 56,
                    789_012_345,
                ),
                '12:34:56.789',
            ],
        ];
    }
}
