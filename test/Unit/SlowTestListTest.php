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

use Ergebnis\PHPUnit\SlowTestDetector\Comparator;
use Ergebnis\PHPUnit\SlowTestDetector\Count;
use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumCount;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestList;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestDescription;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\SlowTestList
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Count
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\SlowTestListIsEmpty
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumCount
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestDescription
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier
 */
final class SlowTestListTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsSlowTestList()
    {
        $faker = self::faker();

        $slowTests = \array_map(static function () use ($faker): SlowTest {
            return SlowTest::create(
                TestIdentifier::fromString($faker->word()),
                TestDescription::fromString($faker->word()),
                Duration::fromMilliseconds($faker->numberBetween(0)),
                MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(0)))
            );
        }, \range(1, $faker->numberBetween(1, 10)));

        $slowTestList = SlowTestList::create(...$slowTests);

        self::assertSame($slowTests, $slowTestList->toArray());
    }

    public function testCountReturnsCountOfSlowTests()
    {
        $faker = self::faker();

        $slowTests = \array_map(static function () use ($faker): SlowTest {
            return SlowTest::create(
                TestIdentifier::fromString($faker->word()),
                TestDescription::fromString($faker->word()),
                Duration::fromMilliseconds($faker->numberBetween(0)),
                MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(0)))
            );
        }, \range(1, $faker->numberBetween(1, 10)));

        $slowTestList = SlowTestList::create(...$slowTests);

        $expected = Count::fromInt(\count($slowTests));

        self::assertEquals($expected, $slowTestList->count());
    }

    public function testFirstThrowsExceptionWhenSlowTestListIsEmpty()
    {
        $slowTestList = SlowTestList::create();

        $this->expectException(Exception\SlowTestListIsEmpty::class);

        $slowTestList->first();
    }

    public function testFirstReturnsFirstSlowTestWhenSlowTestListIsNotEmpty()
    {
        $faker = self::faker();

        $slowTests = \array_map(static function () use ($faker): SlowTest {
            return SlowTest::create(
                TestIdentifier::fromString($faker->word()),
                TestDescription::fromString($faker->word()),
                Duration::fromMilliseconds($faker->numberBetween(0)),
                MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(0)))
            );
        }, \range(1, $faker->numberBetween(1, 10)));

        $slowTestList = SlowTestList::create(...$slowTests);

        $expected = \reset($slowTests);

        self::assertSame($expected, $slowTestList->first());
    }

    public function testIsEmptyReturnsTrueWhenSlowTestListIsEmpty()
    {
        $slowTestList = SlowTestList::create();

        self::assertTrue($slowTestList->isEmpty());
    }

    public function testIsEmptyReturnsFalseWhenSlowTestListIsNotEmpty()
    {
        $faker = self::faker();

        $slowTests = \array_map(static function () use ($faker): SlowTest {
            return SlowTest::create(
                TestIdentifier::fromString($faker->word()),
                TestDescription::fromString($faker->word()),
                Duration::fromMilliseconds($faker->numberBetween(0)),
                MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(0)))
            );
        }, \range(1, $faker->numberBetween(1, 10)));

        $slowTestList = SlowTestList::create(...$slowTests);

        self::assertFalse($slowTestList->isEmpty());
    }

    public function testLimitToReturnsSlowTestListLimitedToMaximumCountWhenSlowTestListHasFewerSlowTests()
    {
        $faker = self::faker();

        $maximumCount = MaximumCount::fromCount(Count::fromInt($faker->numberBetween(2, 10)));

        $slowTests = \array_map(static function () use ($faker): SlowTest {
            return SlowTest::create(
                TestIdentifier::fromString($faker->word()),
                TestDescription::fromString($faker->word()),
                Duration::fromMilliseconds($faker->numberBetween(0)),
                MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(0)))
            );
        }, \range(1, $faker->numberBetween(1, $maximumCount->toCount()->toInt() - 1)));

        $slowTestList = SlowTestList::create(...$slowTests);

        $limitedToMaximumCount = $slowTestList->limitTo($maximumCount);

        self::assertNotSame($slowTestList, $limitedToMaximumCount);

        $expected = \array_slice(
            $slowTests,
            0,
            $maximumCount->toCount()->toInt()
        );

        self::assertEquals($expected, $limitedToMaximumCount->toArray());
    }

    public function testLimitToReturnsSlowTestListLimitedToMaximumCountWhenSlowTestListHasMoreSlowTests()
    {
        $faker = self::faker();

        $maximumCount = MaximumCount::fromCount(Count::fromInt($faker->numberBetween(2, 10)));

        $slowTests = \array_map(static function () use ($faker): SlowTest {
            return SlowTest::create(
                TestIdentifier::fromString($faker->word()),
                TestDescription::fromString($faker->word()),
                Duration::fromMilliseconds($faker->numberBetween(0)),
                MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(0)))
            );
        }, \range(1, $faker->numberBetween($maximumCount->toCount()->toInt() + 1, $maximumCount->toCount()->toInt() + 10)));

        $slowTestList = SlowTestList::create(...$slowTests);

        $limitedToMaximumCount = $slowTestList->limitTo($maximumCount);

        self::assertNotSame($slowTestList, $limitedToMaximumCount);

        $expected = \array_slice(
            $slowTests,
            0,
            $maximumCount->toCount()->toInt()
        );

        self::assertEquals($expected, $limitedToMaximumCount->toArray());
    }

    public function testSortByDurationDescendingReturnsSlowTestListWhereSlowTestsAreSortedByDurationDescending()
    {
        $faker = self::faker();

        $durationComparator = new Comparator\DurationComparator();

        $slowTests = \array_map(static function () use ($faker): SlowTest {
            return SlowTest::create(
                TestIdentifier::fromString($faker->word()),
                TestDescription::fromString($faker->word()),
                Duration::fromMilliseconds($faker->numberBetween(0)),
                MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(0)))
            );
        }, \range(1, $faker->numberBetween(1, 10)));

        $slowTestList = SlowTestList::create(...$slowTests);

        $sortedByDurationDescending = $slowTestList->sortByDurationDescending();

        self::assertNotSame($slowTestList, $sortedByDurationDescending);

        $expected = $slowTests;

        \usort($expected, static function (SlowTest $one, SlowTest $two) use ($durationComparator): int {
            return $durationComparator->compare(
                $two->duration(),
                $one->duration()
            );
        });

        self::assertEquals($expected, $sortedByDurationDescending->toArray());
    }

    public function testSortByMaximumDurationDescendingReturnsSlowTestListWhereSlowTestsAreSortedByMaximumDurationDescending()
    {
        $faker = self::faker();

        $durationComparator = new Comparator\DurationComparator();

        $slowTests = \array_map(static function () use ($faker): SlowTest {
            return SlowTest::create(
                TestIdentifier::fromString($faker->word()),
                TestDescription::fromString($faker->word()),
                Duration::fromMilliseconds($faker->numberBetween(0)),
                MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(0)))
            );
        }, \range(1, $faker->numberBetween(1, 10)));

        $slowTestList = SlowTestList::create(...$slowTests);

        $sortedByMaximumDurationDescending = $slowTestList->sortByMaximumDurationDescending();

        self::assertNotSame($slowTestList, $sortedByMaximumDurationDescending);

        $expected = $slowTests;

        \usort($expected, static function (SlowTest $one, SlowTest $two) use ($durationComparator): int {
            return $durationComparator->compare(
                $two->maximumDuration()->toDuration(),
                $one->maximumDuration()->toDuration()
            );
        });

        self::assertEquals($expected, $sortedByMaximumDurationDescending->toArray());
    }
}
