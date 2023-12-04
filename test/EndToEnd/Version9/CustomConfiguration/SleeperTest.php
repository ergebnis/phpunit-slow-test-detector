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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version9\CustomConfiguration;

use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
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

    /**
     * This DocBlock is intentionally left without a useful comment or annotation.
     */
    public function testSleeperSleepsWithDocBlockWithoutSlowThresholdAnnotation(): void
    {
        $milliseconds = 90;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @maximumDuration 3.14
     */
    public function testSleeperSleepsWithDocBlockWithMaximumDurationAnnotationWhereValueIsNotAnInt(): void
    {
        $milliseconds = 110;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @maximumDuration 140
     */
    public function testSleeperSleepsShorterThanMaximumDurationFromMaximumDurationAnnotation(): void
    {
        $milliseconds = 130;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @maximumDuration 150
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromMaximumDurationAnnotation(): void
    {
        $milliseconds = 150;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @maximumDuration 160
     *
     * @slowThreshold 120
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromMaximumDurationAnnotationWhenSlowThresholdAnnotationIsPresentAfterMaximumDuration(): void
    {
        $milliseconds = 170;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @slowThreshold 120
     *
     * @maximumDuration 180
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromMaximumDurationAnnotationWhenSlowThresholdAnnotationIsPresentBeforeMaximumDuration(): void
    {
        $milliseconds = 200;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @see https://github.com/johnkary/phpunit-speedtrap/blob/1.0/src/JohnKary/PHPUnit/Listener/SpeedTrapListener.php#L309-L331
     *
     * @slowThreshold 3.14
     */
    public function testSleeperSleepsWithDocBlockWithSlowThresholdAnnotationWhereValueIsNotAnInt(): void
    {
        $milliseconds = 40;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @see https://github.com/johnkary/phpunit-speedtrap/blob/1.0/src/JohnKary/PHPUnit/Listener/SpeedTrapListener.php#L309-L331
     *
     * @slowThreshold 100
     */
    public function testSleeperSleepsShorterThanMaximumDurationFromSlowThresholdAnnotation(): void
    {
        $milliseconds = 80;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @see https://github.com/johnkary/phpunit-speedtrap/blob/1.0/src/JohnKary/PHPUnit/Listener/SpeedTrapListener.php#L309-L331
     *
     * @slowThreshold 200
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromSlowThresholdAnnotation(): void
    {
        $milliseconds = 220;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }
}
