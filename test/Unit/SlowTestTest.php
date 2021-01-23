<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-collector
 */

namespace Ergebnis\PHPUnit\SlowTestCollector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestCollector\SlowTest;
use Ergebnis\Test\Util;
use PHPUnit\Event;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\PHPUnit\SlowTestCollector\SlowTest
 */
final class SlowTestTest extends Framework\TestCase
{
    use Util\Helper;

    public function testFromTestAndDurationReturnsSlowTest(): void
    {
        $faker = self::faker();

        $test = new Event\Code\Test(
            self::class,
            $faker->word,
            $faker->word
        );

        $duration = Event\Telemetry\Duration::fromSeconds($faker->numberBetween());

        $slowTest = SlowTest::fromTestAndDuration(
            $test,
            $duration
        );

        self::assertSame($test, $slowTest->test());
        self::assertSame($duration, $slowTest->duration());
    }
}
