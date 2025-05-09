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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version06\TestCase\Combination;

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
    public function sleepWithBeforeClassAnnotation()
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

    public function testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration()
    {
        $milliseconds = 10;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @dataProvider provideMillisecondsGreaterThanMaximumDurationFromXmlConfiguration
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider(int $milliseconds)
    {
        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @return \Generator<int, array{0: int}>
     */
    public static function provideMillisecondsGreaterThanMaximumDurationFromXmlConfiguration(): \Generator
    {
        $values = \range(
            200,
            300,
            100
        );

        foreach ($values as $value) {
            Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();

            yield $value => [
                $value,
            ];
        }
    }
}
