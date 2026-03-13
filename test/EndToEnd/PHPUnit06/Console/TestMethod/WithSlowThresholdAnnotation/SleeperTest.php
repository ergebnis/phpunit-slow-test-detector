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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit06\Console\TestMethod\WithSlowThresholdAnnotation;

use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture\Sleeper
 */
final class SleeperTest extends Framework\TestCase
{
    /**
     * @slowThreshold 3.14
     *
     * @see https://github.com/johnkary/phpunit-speedtrap/blob/1.0/src/JohnKary/PHPUnit/Listener/SpeedTrapListener.php#L309-L331
     */
    public function testSleeperSleepsShorterThanMaximumDurationFromXmlConfigurationWhenTestMethodHasInvalidSlowThresholdAnnotation()
    {
        $milliseconds = 10;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @slowThreshold 3.14
     *
     * @see https://github.com/johnkary/phpunit-speedtrap/blob/1.0/src/JohnKary/PHPUnit/Listener/SpeedTrapListener.php#L309-L331
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWhenTestMethodHasInvalidSlowThresholdAnnotation()
    {
        $milliseconds = 200;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @slowThreshold 200
     *
     * @see https://github.com/johnkary/phpunit-speedtrap/blob/1.0/src/JohnKary/PHPUnit/Listener/SpeedTrapListener.php#L309-L331
     */
    public function testSleeperSleepsShorterThanSlowThresholdFromAnnotationWhenTestMethodHasValidSlowThresholdAnnotation()
    {
        $milliseconds = 10;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @slowThreshold 200
     *
     * @see https://github.com/johnkary/phpunit-speedtrap/blob/1.0/src/JohnKary/PHPUnit/Listener/SpeedTrapListener.php#L309-L331
     */
    public function testSleeperSleepsLongerThanSlowThresholdFromAnnotationWhenTestMethodHasValidSlowThresholdAnnotation()
    {
        $milliseconds = 300;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }
}
