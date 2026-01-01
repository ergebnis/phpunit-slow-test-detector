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
use Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidPhaseIdentifier
 */
final class PhaseIdentifierTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::empty
     */
    public function testFromStringRejectsInvalidValue(string $value)
    {
        $this->expectException(Exception\InvalidPhaseIdentifier::class);

        PhaseIdentifier::fromString($value);
    }

    public function testFromStringReturnsPhaseIdentifier()
    {
        $value = self::faker()->word();

        $testIdentifier = PhaseIdentifier::fromString($value);

        self::assertSame($value, $testIdentifier->toString());
    }
}
