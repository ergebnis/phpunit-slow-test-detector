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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Example;

use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Test\Example\Sleeper
 */
final class SleeperTest extends Framework\TestCase
{
    use Test\Util\Helper;

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

    /**
     * This DocBlock is intentionally left without a useful comment or annotation.
     */
    public function testSleeperSleepsWithDocBlockWithoutSlowThresholdAnnotation(): void
    {
        $milliseconds = 500;

        $sleeper = Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @slowThreshold 3.14
     */
    public function testSleeperSleepsWithDocBlockWithSlowThresholdAnnotationWhereValueIsNotAnInt(): void
    {
        $milliseconds = 500;

        $sleeper = Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @see https://github.com/johnkary/phpunit-speedtrap/blob/1.0/src/JohnKary/PHPUnit/Listener/SpeedTrapListener.php#L309-L331
     *
     * @slowThreshold 400
     *
     * @dataProvider provideMilliseconds
     */
    public function testSleeperSleepsWithSlowThresholdAnnotation(int $milliseconds): void
    {
        $sleeper = Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @return \Generator<int, array{0: int}>
     */
    public function provideMilliseconds(): \Generator
    {
        $values = [
            250,
            500,
        ];

        foreach ($values as $value) {
            yield [
                $value,
            ];
        }
    }
}
