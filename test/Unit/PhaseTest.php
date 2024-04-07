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

use Ergebnis\PHPUnit\SlowTestDetector\Phase;
use Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\Time;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Phase
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\PhaseStart
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Time
 */
final class PhaseTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsPhase()
    {
        $faker = self::faker();

        $phaseIdentifier = PhaseIdentifier::fromString($faker->word());
        $startTime = Time::fromSecondsAndNanoseconds(
            $faker->numberBetween(0, 100),
            $faker->numberBetween(0, 999999999)
        );
        $stopTime = Time::fromSecondsAndNanoseconds(
            $faker->numberBetween(101, 999),
            $faker->numberBetween(0, 999999999)
        );

        $phase = Phase::create(
            $phaseIdentifier,
            $startTime,
            $stopTime
        );

        self::assertSame($phaseIdentifier, $phase->phaseIdentifier());
        self::assertSame($startTime, $phase->startTime());
        self::assertSame($stopTime, $phase->stopTime());
        self::assertEquals($stopTime->duration($startTime), $phase->duration());
    }
}
