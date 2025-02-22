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
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 */
final class MaximumDurationTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromDurationReturnsMaximumDuration()
    {
        $faker = self::faker();

        $duration = Duration::fromMilliseconds($faker->numberBetween(0));

        $maximumDuration = MaximumDuration::fromDuration($duration);

        self::assertSame($duration, $maximumDuration->toDuration());
    }

    public function testDefaultReturnsMaximumDuration()
    {
        $maximumDuration = MaximumDuration::default();

        $expected = Duration::fromMilliseconds(500);

        self::assertEquals($expected, $maximumDuration->toDuration());
    }
}
