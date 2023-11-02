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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(SlowTest::class)]
#[Framework\Attributes\UsesClass(Duration::class)]
#[Framework\Attributes\UsesClass(TestIdentifier::class)]
final class SlowTestTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsSlowTest(): void
    {
        $faker = self::faker();

        $testIdentifier = TestIdentifier::fromString($faker->word());
        $duration = Duration::fromMilliseconds($faker->numberBetween(0));
        $maximumDuration = Duration::fromMilliseconds($faker->numberBetween(0));

        $slowTest = SlowTest::create(
            $testIdentifier,
            $duration,
            $maximumDuration,
        );

        self::assertSame($testIdentifier, $slowTest->testIdentifier());
        self::assertSame($duration, $slowTest->duration());
        self::assertSame($maximumDuration, $slowTest->maximumDuration());
    }
}
