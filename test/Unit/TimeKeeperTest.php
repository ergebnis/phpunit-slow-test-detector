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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\Time;
use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\PhaseNotStarted
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Phase
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\PhaseStart
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Time
 */
final class TimeKeeperTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testStopThrowsPhaseNotStartedExceptionWhenPhaseHasNotBeenStarted(): void
    {
        $faker = self::faker();

        $phaseIdentifier = PhaseIdentifier::fromString($faker->word());
        $stopTime = Time::fromSecondsAndNanoseconds(
            $faker->numberBetween(0),
            $faker->numberBetween(0, 999999999)
        );

        $timeKeeper = new TimeKeeper();

        $this->expectException(Exception\PhaseNotStarted::class);

        $timeKeeper->stop(
            $phaseIdentifier,
            $stopTime
        );
    }

    public function testStopReturnsPhaseWhenPhaseHasBeenStarted(): void
    {
        $faker = self::faker();

        $phaseIdentifier = PhaseIdentifier::fromString($faker->word());
        $startTime = Time::fromSecondsAndNanoseconds(
            $faker->numberBetween(0),
            $faker->numberBetween(0, 999999999 - 1)
        );
        $stopTime = Time::fromSecondsAndNanoseconds(
            $faker->numberBetween($startTime->seconds() + 1),
            $faker->numberBetween($startTime->nanoseconds() + 1, 999999999)
        );

        $timeKeeper = new TimeKeeper();

        $timeKeeper->start(
            $phaseIdentifier,
            $startTime
        );

        $phase = $timeKeeper->stop(
            $phaseIdentifier,
            $stopTime
        );

        self::assertSame($phaseIdentifier, $phase->phaseIdentifier());
        self::assertSame($startTime, $phase->startTime());
        self::assertSame($stopTime, $phase->stopTime());
        self::assertEquals($stopTime->duration($startTime), $phase->duration());
    }
}
