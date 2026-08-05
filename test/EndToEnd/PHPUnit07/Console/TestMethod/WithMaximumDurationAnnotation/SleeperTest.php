<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2026 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit07\Console\TestMethod\WithMaximumDurationAnnotation;

use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\Sleeper
 */
final class SleeperTest extends Framework\TestCase
{
    /**
     * @maximumDuration 3.14
     */
    public function testSleeperSleepsShorterThanMaximumDurationFromXmlConfigurationWhenTestMethodHasInvalidMaximumDurationAnnotation(): void
    {
        $milliseconds = 10;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @maximumDuration 3.14
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenTestMethodHasInvalidMaximumDurationAnnotation(): void
    {
        $milliseconds = 100;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @maximumDuration 100
     */
    public function testSleeperSleepsShorterThanMaximumDurationFromAnnotationWhenTestMethodHasValidMaximumDurationAnnotation(): void
    {
        $milliseconds = 10;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @dataProvider provideDataWhereDataNameIsInteger
     *
     * @maximumDuration 100
     */
    public function testSleeperSleepsShorterThanMaximumDurationFromAnnotationWithNumericDataProvider(): void
    {
        $milliseconds = 75;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @return list<array{0: string}>
     */
    public static function provideDataWhereDataNameIsInteger(): array
    {
        return [
            [
                'bar',
            ],
        ];
    }

    /**
     * @dataProvider provideDataWhereDataNameIsString
     *
     * @maximumDuration 100
     */
    public function testSleeperSleepsShorterThanMaximumDurationFromAnnotationWithNamedDataProvider(): void
    {
        $milliseconds = 75;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function provideDataWhereDataNameIsString(): array
    {
        return [
            'foo' => [
                'bar',
            ],
        ];
    }

    /**
     * @maximumDuration 100
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromAnnotationWhenTestMethodHasValidMaximumDurationAnnotation(): void
    {
        $milliseconds = 150;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }
}
