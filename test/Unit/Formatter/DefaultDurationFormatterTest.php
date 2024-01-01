<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Formatter;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Formatter;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Formatter\DefaultDurationFormatter
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 */
final class DefaultDurationFormatterTest extends Framework\TestCase
{
    /**
     * @dataProvider provideDurationAndFormattedDuration
     */
    public function testFormatFormats(
        Duration $duration,
        string $formattedDuration
    ): void {
        $formatter = new Formatter\DefaultDurationFormatter();

        self::assertSame($formattedDuration, $formatter->format($duration));
    }

    /**
     * @return \Generator<string, array{0: Duration, 1: string}>
     */
    public static function provideDurationAndFormattedDuration(): iterable
    {
        $values = [
            'zero' => [
                Duration::fromSecondsAndNanoseconds(
                    0,
                    0,
                ),
                '0.000',
            ],
            'milliseconds' => [
                Duration::fromSecondsAndNanoseconds(
                    0,
                    123_999_000,
                ),
                '0.123',
            ],
            'seconds-digits-one' => [
                Duration::fromSecondsAndNanoseconds(
                    1,
                    234_456_789,
                ),
                '1.234',
            ],
            'seconds-digits-two' => [
                Duration::fromSecondsAndNanoseconds(
                    12,
                    345_678_912,
                ),
                '12.345',
            ],
            'minutes-digits-one' => [
                Duration::fromSecondsAndNanoseconds(
                    1 * 60 + 23,
                    456_789_012,
                ),
                '1:23.456',
            ],
            'minutes-digits-two' => [
                Duration::fromSecondsAndNanoseconds(
                    12 * 60 + 34,
                    567_890_123,
                ),
                '12:34.567',
            ],
            'hours-digits-one' => [
                Duration::fromSecondsAndNanoseconds(
                    60 * 60 + 23 * 60 + 45,
                    567_890_123,
                ),
                '1:23:45.567',
            ],
            'hours-digits-two' => [
                Duration::fromSecondsAndNanoseconds(
                    12 * 60 * 60 + 34 * 60 + 56,
                    789_012_345,
                ),
                '12:34:56.789',
            ],
        ];

        foreach ($values as $key => [$duration, $formattedDuration]) {
            yield $key => [
                $duration,
                $formattedDuration,
            ];
        }
    }
}
