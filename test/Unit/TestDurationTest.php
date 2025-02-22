<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestDuration;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\TestDuration
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 */
final class TestDurationTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromDurationReturnsTestDuration()
    {
        $faker = self::faker();

        $duration = Duration::fromMilliseconds($faker->numberBetween(0));

        $testDuration = TestDuration::fromDuration($duration);

        self::assertSame($duration, $testDuration->toDuration());
    }
}
