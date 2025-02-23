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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Collector;

use Ergebnis\PHPUnit\SlowTestDetector\Collector;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestList;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestDescription;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Collector\DefaultCollector
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTestList
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestDescription
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier
 */
final class DefaultCollectorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCollectSlowTestCollectsSlowTests()
    {
        $faker = self::faker()->unique();

        $one = SlowTest::create(
            TestIdentifier::fromString($faker->word()),
            TestDescription::fromString($faker->word()),
            Duration::fromMilliseconds($faker->numberBetween(0)),
            MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(0)))
        );

        $two = SlowTest::create(
            TestIdentifier::fromString($faker->word()),
            TestDescription::fromString($faker->word()),
            Duration::fromMilliseconds($faker->numberBetween(0)),
            MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(0)))
        );

        $collector = new Collector\DefaultCollector();

        $collector->collectSlowTest($one);
        $collector->collectSlowTest($two);

        $expected = SlowTestList::create(
            $one,
            $two
        );

        self::assertEquals($expected, $collector->slowTestList());
    }

    public function testCollectSlowTestCollectsSlowerTestWithSameTestIdentifier()
    {
        $faker = self::faker();

        $one = SlowTest::create(
            TestIdentifier::fromString($faker->word()),
            TestDescription::fromString($faker->word()),
            Duration::fromMilliseconds($faker->numberBetween(0)),
            MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(0, 999999999 - 1)))
        );

        $two = SlowTest::create(
            $one->testIdentifier(),
            TestDescription::fromString($faker->word()),
            Duration::fromSecondsAndNanoseconds(
                $one->duration()->seconds(),
                $one->duration()->nanoseconds() + 1
            ),
            $one->maximumDuration()
        );

        $collector = new Collector\DefaultCollector();

        $collector->collectSlowTest($one);
        $collector->collectSlowTest($two);

        $expected = SlowTestList::create($two);

        self::assertEquals($expected, $collector->slowTestList());
    }

    public function testCollectSlowTestDoesNotCollectFasterTestWithSameTestIdentifier()
    {
        $faker = self::faker();

        $one = SlowTest::create(
            TestIdentifier::fromString($faker->word()),
            TestDescription::fromString($faker->word()),
            Duration::fromMilliseconds($faker->numberBetween(0)),
            MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(1, 999999999)))
        );

        $two = SlowTest::create(
            $one->testIdentifier(),
            TestDescription::fromString($faker->word()),
            Duration::fromSecondsAndNanoseconds(
                $one->duration()->seconds(),
                $one->duration()->nanoseconds() - 1
            ),
            $one->maximumDuration()
        );

        $collector = new Collector\DefaultCollector();

        $collector->collectSlowTest($one);
        $collector->collectSlowTest($two);

        $expected = SlowTestList::create($one);

        self::assertEquals($expected, $collector->slowTestList());
    }
}
