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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\TestMethod\WithRunInSeparateProcessAttribute;

use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Test\Fixture\Sleeper::class)]
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
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    protected function assertPreConditions(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    protected function assertPostConditions(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    protected function tearDown(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    #[Framework\Attributes\Before]
    public function sleepWithBeforeAttribute(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    #[Framework\Attributes\BeforeClass]
    public static function sleepWithBeforeClassAttribute(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    #[Framework\Attributes\After]
    public function sleepWithAfterAttribute(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    #[Framework\Attributes\AfterClass]
    public static function sleepWithAfterClassAttribute(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    public function testSleeperSleepsShorterThanMaximumDurationFromXmlConfiguration(): void
    {
        $milliseconds = 50;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    #[Framework\Attributes\RunInSeparateProcess]
    public function testSleeperSleepsShorterThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAttribute(): void
    {
        $milliseconds = 10;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    public function testSleeperSleepsLongerThanMaximumDurationFromXmlConfiguration(): void
    {
        $milliseconds = 200;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    #[Framework\Attributes\RunInSeparateProcess]
    public function testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAttribute(): void
    {
        $milliseconds = 300;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }
}
