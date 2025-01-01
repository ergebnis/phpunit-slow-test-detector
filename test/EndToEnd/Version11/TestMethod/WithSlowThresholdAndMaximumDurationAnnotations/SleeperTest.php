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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version11\TestMethod\WithSlowThresholdAndMaximumDurationAnnotations;

use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Test\Fixture\Sleeper::class)]
final class SleeperTest extends Framework\TestCase
{
    /**
     * @slowThreshold 400
     *
     * @maximumDuration 200
     */
    public function testSleeperSleepsShorterThanMaximumDurationFromAnnotationWhenTestMethodHasMaximumDurationAndSlowThresholdAnnotations(): void
    {
        $milliseconds = 50;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }

    /**
     * @slowThreshold 400
     *
     * @maximumDuration 200
     */
    public function testSleeperSleepsLongerThanMaximumDurationFromAnnotationWhenTestMethodHasMaximumDurationAndSlowThresholdAnnotations(): void
    {
        $milliseconds = 300;

        $sleeper = Test\Fixture\Sleeper::fromMilliseconds($milliseconds);

        $sleeper->sleep();

        self::assertSame($milliseconds, $sleeper->milliseconds());
    }
}
