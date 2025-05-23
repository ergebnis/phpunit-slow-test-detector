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
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestDescription;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestDescription
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier
 */
final class SlowTestTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsSlowTest()
    {
        $faker = self::faker();

        $testIdentifier = TestIdentifier::fromString($faker->word());
        $testDescription = TestDescription::fromString($faker->word());
        $duration = Duration::fromMilliseconds($faker->numberBetween(0));
        $maximumDuration = MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(0)));

        $slowTest = SlowTest::create(
            $testIdentifier,
            $testDescription,
            $duration,
            $maximumDuration
        );

        self::assertSame($testIdentifier, $slowTest->testIdentifier());
        self::assertSame($testDescription, $slowTest->testDescription());
        self::assertSame($duration, $slowTest->duration());
        self::assertSame($maximumDuration, $slowTest->maximumDuration());
    }
}
