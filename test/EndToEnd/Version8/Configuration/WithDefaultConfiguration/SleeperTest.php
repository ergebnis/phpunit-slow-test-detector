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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version8\Configuration\WithDefaultConfiguration;

use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\Sleeper
 */
final class SleeperTest extends Framework\TestCase
{
    public static function setUpBeforeClass(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    public static function tearDownAfterClass(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    protected function setUp(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(50)->sleep();
    }

    protected function tearDown(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(50)->sleep();
    }

    public function testSleeperSleepsShorterThanDefaultMaximumDuration(): void
    {
        $milliseconds = 50;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    public function testSleeperSleepsLongerThanDefaultMaximumDuration(): void
    {
        $milliseconds = 750;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * This DocBlock is intentionally left without a useful comment or annotation.
     */
    public function testSleeperSleepsShorterThanDefaultMaximumDurationWithUselessDocBlock(): void
    {
        $milliseconds = 50;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * This DocBlock is intentionally left without a useful comment or annotation.
     */
    public function testSleeperSleepsLongerThanDefaultMaximumDurationWithUselessDocBlock(): void
    {
        $milliseconds = 800;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @maximumDuration 3.14
     */
    public function testSleeperSleepsShorterThanDefaultMaximumDurationWithInvalidMaximumDurationAnnotation(): void
    {
        $milliseconds = 50;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @maximumDuration 3.14
     */
    public function testSleeperSleepsLongerThanDefaultMaximumDurationWithInvalidMaximumDurationAnnotation(): void
    {
        $milliseconds = 850;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @maximumDuration 800
     */
    public function testSleeperSleepsShorterThanMaximumDurationFromAnnotationWithValidMaximumDurationAnnotation(): void
    {
        $milliseconds = 50;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @maximumDuration 800
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidMaximumDurationAnnotation(): void
    {
        $milliseconds = 900;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @see https://github.com/johnkary/phpunit-speedtrap/blob/1.0/src/JohnKary/PHPUnit/Listener/SpeedTrapListener.php#L309-L331
     *
     * @slowThreshold 3.14
     */
    public function testSleeperSleepsShorterThanDefaultMaximumDurationWithInvalidSlowThresholdAnnotation(): void
    {
        $milliseconds = 50;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @see https://github.com/johnkary/phpunit-speedtrap/blob/1.0/src/JohnKary/PHPUnit/Listener/SpeedTrapListener.php#L309-L331
     *
     * @slowThreshold 3.14
     */
    public function testSleeperSleepsLongerThanDefaultMaximumDurationWithInvalidSlowThresholdAnnotation(): void
    {
        $milliseconds = 950;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @see https://github.com/johnkary/phpunit-speedtrap/blob/1.0/src/JohnKary/PHPUnit/Listener/SpeedTrapListener.php#L309-L331
     *
     * @slowThreshold 950
     */
    public function testSleeperSleepsShorterThanSlowThresholdFromAnnotationWithValidSlowThresholdAnnotation(): void
    {
        $milliseconds = 50;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @see https://github.com/johnkary/phpunit-speedtrap/blob/1.0/src/JohnKary/PHPUnit/Listener/SpeedTrapListener.php#L309-L331
     *
     * @slowThreshold 900
     */
    public function testSleeperSleepsLongerThanSlowThresholdFromAnnotationWithValidSlowThresholdAnnotation(): void
    {
        $milliseconds = 1000;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @maximumDuration 1000
     *
     * @slowThreshold 300
     */
    public function testSleeperSleepsShorterThanMaximumDurationFromAnnotationWithValidMaximumDurationAndSlowThresholdAnnotation(): void
    {
        $milliseconds = 50;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @maximumDuration 1000
     *
     * @slowThreshold 300
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidMaximumDurationAndSlowThresholdAnnotation(): void
    {
        $milliseconds = 1050;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @slowThreshold 300
     *
     * @maximumDuration 1000
     */
    public function testSleeperSleepsShorterThanMaximumDurationFromAnnotationWithValidSlowThresholdAndMaximumDurationAnnotation(): void
    {
        $milliseconds = 50;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @slowThreshold 300
     *
     * @maximumDuration 1000
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromAnnotationWithValidSlowThresholdAndMaximumDurationAnnotation(): void
    {
        $milliseconds = 1100;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @dataProvider provideMillisecondsGreaterThanDefaultMaximumDuration
     */
    public function testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider(int $milliseconds): void
    {
        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @return \Generator<int, array{0: int}>
     */
    public static function provideMillisecondsGreaterThanDefaultMaximumDuration(): iterable
    {
        $values = \range(
            500,
            600,
            50,
        );

        foreach ($values as $value) {
            yield $value => [
                $value,
            ];
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function testSleeperSleepsShorterThanDefaultMaximumDurationWhenRunningInSeparateProcess(): void
    {
        $milliseconds = 50;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @runInSeparateProcess
     */
    public function testSleeperSleepsLongerThanDefaultMaximumDurationWhenRunningInSeparateProcess(): void
    {
        $milliseconds = 750;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }
}
