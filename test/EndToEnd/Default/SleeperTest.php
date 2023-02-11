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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Default;

use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\Sleeper
 */
final class SleeperTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testSleeperDoesNotSleepAtAll(): void
    {
        $milliseconds = 0;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    public function testSleeperSleepsJustBelowDefaultMaximumDuration(): void
    {
        $milliseconds = 400;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    public function testSleeperSleepsJustAboveDefaultMaximumDuration(): void
    {
        $milliseconds = 600;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @dataProvider provideMillisecondsGreaterThanDefaultMaximumDuration
     */
    public function testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider(int $milliseconds): void
    {
        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @return \Generator<int, array{0: int}>
     */
    public static function provideMillisecondsGreaterThanDefaultMaximumDuration(): \Generator
    {
        $values = \range(
            700,
            1600,
            100,
        );

        foreach ($values as $value) {
            yield $value => [
                $value,
            ];
        }
    }
}
