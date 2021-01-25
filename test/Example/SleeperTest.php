<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Example;

use Ergebnis\Test\Util;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Test\Example\Sleeper
 */
final class SleeperTest extends Framework\TestCase
{
    use Util\Helper;

    public function testSleeperDoesNotSleepAtAll(): void
    {
        $milliseconds = 0;

        $sleeper = Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    public function testSleeperSleepsOneQuarterOfASecond(): void
    {
        $milliseconds = 250;

        $sleeper = Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    public function testSleeperSleepsHalfASeconds(): void
    {
        $milliseconds = 500;

        $sleeper = Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    public function testSleeperSleepsThreeQuartersOfASecond(): void
    {
        $milliseconds = 750;

        $sleeper = Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    public function testSleeperSleepsOneSecond(): void
    {
        $milliseconds = 1000;

        $sleeper = Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }
}
