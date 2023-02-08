<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2023 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\MaximumNumber\Ten;

use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Sleeper
 */
final class SleeperTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testSleeperDoesNotSleepAtAll(): void
    {
        $milliseconds = 0;

        $sleeper = Test\EndToEnd\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    public function testSleeperSleepsOneHundredFiftyMilliseconds(): void
    {
        $milliseconds = 150;

        $sleeper = Test\EndToEnd\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    public function testSleeperSleepsTwoHundredMilliseconds(): void
    {
        $milliseconds = 200;

        $sleeper = Test\EndToEnd\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    public function testSleeperSleepsThreeHundredMilliseconds(): void
    {
        $milliseconds = 300;

        $sleeper = Test\EndToEnd\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    public function testSleeperSleepsOneSecond(): void
    {
        $milliseconds = 1000;

        $sleeper = Test\EndToEnd\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * This DocBlock is intentionally left without a useful comment or annotation.
     */
    public function testSleeperSleepsWithDocBlockWithoutSlowThresholdAnnotation(): void
    {
        $milliseconds = 400;

        $sleeper = Test\EndToEnd\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @slowThreshold 3.14
     */
    public function testSleeperSleepsWithDocBlockWithSlowThresholdAnnotationWhereValueIsNotAnInt(): void
    {
        $milliseconds = 450;

        $sleeper = Test\EndToEnd\Sleeper::fromMilliseconds($milliseconds);

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
        $sleeper = Test\EndToEnd\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @return \Generator<int, array{0: int}>
     */
    public static function provideMilliseconds(): \Generator
    {
        $values = [
            250,
            550,
        ];

        foreach ($values as $value) {
            yield [
                $value,
            ];
        }
    }
}
