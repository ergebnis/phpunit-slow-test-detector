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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\File;
use Ergebnis\PHPUnit\SlowTestDetector\Line;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestLocation;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\TestLocation
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\File
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Line
 */
final class TestLocationTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsTestLocation(): void
    {
        $faker = self::faker();

        $file = File::fromString($faker->slug());
        $line = Line::fromInt($faker->numberBetween(1));

        $testLocation = TestLocation::create(
            $file,
            $line,
        );

        self::assertSame($file, $testLocation->file());
        self::assertSame($line, $testLocation->line());
    }
}
