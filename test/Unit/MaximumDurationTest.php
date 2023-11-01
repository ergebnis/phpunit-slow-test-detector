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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\DataProvider;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(MaximumDuration::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidMilliseconds::class)]
final class MaximumDurationTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'lessThanZero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    public function testFromMillisecondsRejectsInvalidValue(int $milliseconds): void
    {
        $this->expectException(Exception\InvalidMilliseconds::class);

        MaximumDuration::fromMilliseconds($milliseconds);
    }

    #[Framework\Attributes\DataProvider('provideMillisecondsAndDuration')]
    public function testFromMillisecondsReturnsMaximumDuration(
        int $milliseconds,
        Duration $duration,
    ): void {
        $maximumDuration = MaximumDuration::fromMilliseconds($milliseconds);

        self::assertEquals($duration, $maximumDuration->toDuration());
    }

    /**
     * @return \Generator<int, array{0: int, 1: Duration}>
     */
    public static function provideMillisecondsAndDuration(): \Generator
    {
        $values = [
            1 => Duration::fromSecondsAndNanoseconds(0, 1_000_000),
            999 => Duration::fromSecondsAndNanoseconds(0, 999_000_000),
            1_000 => Duration::fromSecondsAndNanoseconds(1, 0),
            1_234 => Duration::fromSecondsAndNanoseconds(1, 234_000_000),
        ];

        foreach ($values as $milliseconds => $duration) {
            yield $milliseconds => [
                $milliseconds,
                $duration,
            ];
        }
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'lessThanZero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    public function testFromSecondsRejectsInvalidValue(int $seconds): void
    {
        $this->expectException(Exception\InvalidMilliseconds::class);

        MaximumDuration::fromSeconds($seconds);
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testFromSecondsReturnsMaximumDuration(int $seconds): void
    {
        $maximumDuration = MaximumDuration::fromSeconds($seconds);

        $expected = Duration::fromSecondsAndNanoseconds(
            $seconds,
            0,
        );

        self::assertEquals($expected, $maximumDuration->toDuration());
    }
}
