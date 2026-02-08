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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit06\TestMethod\WithRunInSeparateProcessAnnotation;

use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\Sleeper
 */
final class SleeperTest extends Framework\TestCase
{
    public static function setUpBeforeClass()
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    public static function tearDownAfterClass()
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    protected function setUp()
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    protected function assertPreConditions()
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    protected function assertPostConditions()
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    protected function tearDown()
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    /**
     * @before
     */
    public function sleepWithBeforeAnnotation()
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    /**
     * @beforeClass
     */
    public static function sleepWithBeforeClassAnnotation()
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    /**
     * @after
     */
    public function sleepWithAfterAnnotation()
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    /**
     * @afterClass
     */
    public static function sleepWithAfterClassAnnotation()
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    public function testSleeperSleepsShorterThanMaximumDurationFromXmlConfiguration()
    {
        $milliseconds = 10;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @runInSeparateProcess
     */
    public function testSleeperSleepsShorterThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAnnotation()
    {
        $milliseconds = 50;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    public function testSleeperSleepsLongerThanMaximumDurationFromXmlConfiguration()
    {
        $milliseconds = 200;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @runInSeparateProcess
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenMethodHasRunInSeparateProcessAnnotation()
    {
        $milliseconds = 300;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }
}
