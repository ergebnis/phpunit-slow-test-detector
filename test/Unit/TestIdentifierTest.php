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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidTestIdentifier
 */
final class TestIdentifierTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::empty
     */
    public function testFromStringRejectsInvalidValue(string $value)
    {
        $this->expectException(Exception\InvalidTestIdentifier::class);

        TestIdentifier::fromString($value);
    }

    public function testFromStringReturnsTestIdentifier()
    {
        $value = self::faker()->word();

        $testIdentifier = TestIdentifier::fromString($value);

        self::assertSame($value, $testIdentifier->toString());
    }
}
