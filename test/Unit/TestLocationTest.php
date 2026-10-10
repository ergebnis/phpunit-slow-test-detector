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

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestLocation;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\TestLocation
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidTestLocation
 */
final class TestLocationTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::empty
     */
    public function testCreateThrowsInvalidTestLocationWhenFileIsBlankOrEmpty(string $file): void
    {
        $this->expectException(Exception\InvalidTestLocation::class);

        TestLocation::create(
            $file,
            self::faker()->numberBetween(1),
        );
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\IntProvider::lessThanOne
     */
    public function testCreateThrowsInvalidTestLocationWhenLineIsNotGreaterThanZero(int $line): void
    {
        $this->expectException(Exception\InvalidTestLocation::class);

        TestLocation::create(
            self::faker()->slug(),
            $line,
        );
    }

    public function testCreateReturnsTestLocationWhenFileIsNotBlankOrEmptyAndLineIsGreaterThanZero(): void
    {
        $faker = self::faker();

        $file = $faker->slug();
        $line = $faker->numberBetween(1);

        $testLocation = TestLocation::create(
            $file,
            $line,
        );

        self::assertSame($file, $testLocation->file());
        self::assertSame($line, $testLocation->line());
    }
}
