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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestMethod\WithRunInSeparateProcessAnnotation;

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

    protected function tearDown(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    public function testSleeperSleepsShorterThanMaximumDurationFromXmlConfiguration(): void
    {
        $milliseconds = 1;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @runInSeparateProcess
     */
    public function testSleeperSleepsShorterThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAnnotation(): void
    {
        $milliseconds = 1;

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

    /**
     * @runInSeparateProcess
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAnnotation(): void
    {
        $milliseconds = 300;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }
}
