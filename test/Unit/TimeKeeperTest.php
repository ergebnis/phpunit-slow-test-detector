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

use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use Ergebnis\PHPUnit\SlowTestDetector\Time;
use Ergebnis\PHPUnit\SlowTestDetector\TimeKeeper;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(TimeKeeper::class)]
#[Framework\Attributes\UsesClass(Duration::class)]
#[Framework\Attributes\UsesClass(TestIdentifier::class)]
final class TimeKeeperTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testStopReturnsEmptyDurationWhenTestHasNotBeenStarted(): void
    {
        $faker = self::faker();

        $testIdentifier = TestIdentifier::fromString($faker->word());
        $stoppedTime = Time::fromSecondsAndNanoseconds(
            $faker->numberBetween(0),
            $faker->numberBetween(0, 999_999_999),
        );

        $timeKeeper = new TimeKeeper();

        $duration = $timeKeeper->stop(
            $testIdentifier,
            $stoppedTime,
        );

        self::assertSame(0, $duration->seconds());
        self::assertSame(0, $duration->nanoseconds());
    }

    public function testStopReturnsDurationWhenTestHasBeenStarted(): void
    {
        $faker = self::faker();

        $testIdentifier = TestIdentifier::fromString($faker->word());
        $startedTime = Time::fromSecondsAndNanoseconds(
            $faker->numberBetween(0),
            $faker->numberBetween(0, 999_999_999 - 1),
        );
        $stoppedTime = Time::fromSecondsAndNanoseconds(
            $faker->numberBetween($startedTime->seconds() + 1),
            $faker->numberBetween($startedTime->nanoseconds() + 1, 999_999_999),
        );

        $timeKeeper = new TimeKeeper();

        $timeKeeper->start(
            $testIdentifier,
            $startedTime,
        );

        $duration = $timeKeeper->stop(
            $testIdentifier,
            $stoppedTime,
        );

        self::assertEquals($stoppedTime->duration($startedTime), $duration);
    }
}
