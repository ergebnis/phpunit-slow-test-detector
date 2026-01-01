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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\TestCase\Combination;

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

    /**
     * @before
     */
    public function sleepWithBeforeAnnotation(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    #[Framework\Attributes\Before]
    public function sleepWithBeforeAttribute(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    /**
     * @after
     */
    public function sleepWithAfterAnnotation(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    /**
     * @beforeClass
     */
    public function sleepWithBeforeClassAnnotation(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    #[Framework\Attributes\BeforeClass]
    public function sleepWithBeforeClassAttribute(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    #[Framework\Attributes\After]
    public function sleepWithAfterAttribute(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    /**
     * @afterClass
     */
    public function sleepWithAfterClassAnnotation(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    #[Framework\Attributes\AfterClass]
    public function sleepWithAfterClassAttribute(): void
    {
        Test\Fixture\Sleeper::fromMilliseconds(100)->sleep();
    }

    public function testSleeperSleepsLessThanMaximumDurationFromXmlConfiguration(): void
    {
        $milliseconds = 10;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    #[Framework\Attributes\DataProvider('provideMillisecondsGreaterThanMaximumDurationFromXmlConfiguration')]
    public function testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider(int $milliseconds): void
    {
        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @return \Generator<int, array{0: int}>
     */
    public static function provideMillisecondsGreaterThanMaximumDurationFromXmlConfiguration(): iterable
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
