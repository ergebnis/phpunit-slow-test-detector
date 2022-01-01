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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 */
final class SlowTestTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromTestDurationAndMaximumDurationReturnsSlowTest(): void
    {
        $faker = self::faker();

        $test = new Event\Code\Test(
            self::class,
            $faker->word(),
            $faker->word(),
        );

        $duration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 999_999_999),
        );
        $maximumDuration = Event\Telemetry\Duration::fromSecondsAndNanoseconds(
            $faker->numberBetween(),
            $faker->numberBetween(0, 999_999_999),
        );

        $slowTest = SlowTest::fromTestDurationAndMaximumDuration(
            $test,
            $duration,
            $maximumDuration,
        );

        self::assertSame($test, $slowTest->test());
        self::assertSame($duration, $slowTest->duration());
        self::assertSame($maximumDuration, $slowTest->maximumDuration());
    }
}
