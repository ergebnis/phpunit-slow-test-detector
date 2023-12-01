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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Collector;

use Ergebnis\PHPUnit\SlowTestDetector\Collector;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Collector\DefaultCollector
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier
 */
final class DefaultCollectorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCollectCollectsSlowTests(): void
    {
        $faker = self::faker()->unique();

        $one = SlowTest::create(
            TestIdentifier::fromString($faker->word()),
            Duration::fromMilliseconds($faker->numberBetween(0)),
            Duration::fromMilliseconds($faker->numberBetween(0)),
        );

        $two = SlowTest::create(
            TestIdentifier::fromString($faker->word()),
            Duration::fromMilliseconds($faker->numberBetween(0)),
            Duration::fromMilliseconds($faker->numberBetween(0)),
        );

        $collector = new Collector\DefaultCollector();

        $collector->collect($one);
        $collector->collect($two);

        $expected = [
            $one,
            $two,
        ];

        self::assertSame($expected, $collector->collected());
    }

    public function testCollectCollectsSlowerTestWithSameTestIdentifier(): void
    {
        $faker = self::faker();

        $one = SlowTest::create(
            TestIdentifier::fromString($faker->word()),
            Duration::fromMilliseconds($faker->numberBetween(0)),
            Duration::fromMilliseconds($faker->numberBetween(0, 999_999_999 - 1)),
        );

        $two = SlowTest::create(
            $one->testIdentifier(),
            Duration::fromSecondsAndNanoseconds(
                $one->duration()->seconds(),
                $one->duration()->nanoseconds() + 1,
            ),
            $one->maximumDuration(),
        );

        $collector = new Collector\DefaultCollector();

        $collector->collect($one);
        $collector->collect($two);

        $expected = [
            $two,
        ];

        self::assertSame($expected, $collector->collected());
    }

    public function testCollectDoesNotCollectFasterTestWithSameTestIdentifier(): void
    {
        $faker = self::faker();

        $one = SlowTest::create(
            TestIdentifier::fromString($faker->word()),
            Duration::fromMilliseconds($faker->numberBetween(0)),
            Duration::fromMilliseconds($faker->numberBetween(1, 999_999_999)),
        );

        $two = SlowTest::create(
            $one->testIdentifier(),
            Duration::fromSecondsAndNanoseconds(
                $one->duration()->seconds(),
                $one->duration()->nanoseconds() - 1,
            ),
            $one->maximumDuration(),
        );

        $collector = new Collector\DefaultCollector();

        $collector->collect($one);
        $collector->collect($two);

        $expected = [
            $one,
        ];

        self::assertSame($expected, $collector->collected());
    }
}
