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

use Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier;
use Ergebnis\PHPUnit\SlowTestDetector\PhaseStart;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\Time;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\PhaseStart
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Time
 */
final class PhaseStartTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsPhaseStart(): void
    {
        $faker = self::faker();

        $phaseIdentifier = PhaseIdentifier::fromString($faker->word());
        $startTime = Time::fromSecondsAndNanoseconds(
            $faker->numberBetween(0, 999),
            $faker->numberBetween(0, 999_999_999),
        );

        $phaseStart = PhaseStart::create(
            $phaseIdentifier,
            $startTime,
        );

        self::assertSame($phaseIdentifier, $phaseStart->phaseIdentifier());
        self::assertSame($startTime, $phaseStart->startTime());
    }
}
