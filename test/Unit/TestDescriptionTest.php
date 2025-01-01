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

use Ergebnis\DataProvider;
use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestDescription;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\TestDescription
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidTestDescription
 */
final class TestDescriptionTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::empty
     */
    public function testFromStringRejectsInvalidValue(string $value)
    {
        $this->expectException(Exception\InvalidTestDescription::class);

        TestDescription::fromString($value);
    }

    public function testFromStringReturnsTestDescription()
    {
        $value = self::faker()->word();

        $testDescription = TestDescription::fromString($value);

        self::assertSame($value, $testDescription->toString());
    }
}
