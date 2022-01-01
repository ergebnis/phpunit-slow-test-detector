<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2022 Andreas Möller
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

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Formatter\ToMillisecondsDurationFormatter
 */
final class ToMillisecondsDurationFormatterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider provideDurationAndFormattedDuration
     */
    public function testFormatFormats(Event\Telemetry\Duration $duration, string $formattedDuration): void
    {
        $formatter = new Formatter\ToMillisecondsDurationFormatter();

        self::assertSame($formattedDuration, $formatter->format($duration));
    }

    /**
     * @return array<string, array{0: Event\Telemetry\Duration, 1: string}>
     */
    public function provideDurationAndFormattedDuration(): array
    {
        return [
            'zero' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    0,
                ),
                '0 ms',
            ],
            'nanoseconds-rounded-down' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    499_999,
                ),
                '0 ms',
            ],
            'nanoseconds-rounded-up' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    500_000,
                ),
                '1 ms',
            ],
            'milliseconds-one' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    1_000_000,
                ),
                '1 ms',
            ],
            'milliseconds-hundreds' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    0,
                    123 * 1_000_000,
                ),
                '123 ms',
            ],
            'seconds' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    1,
                    1_000_000,
                ),
                '1,001 ms',
            ],
            'thousands-of-seconds' => [
                Event\Telemetry\Duration::fromSecondsAndNanoseconds(
                    1_234,
                    567_890_123,
                ),
                '1,234,568 ms',
            ],
        ];
    }
}
