<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

use Ergebnis\DataProvider;
use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(TestIdentifier::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidTestIdentifier::class)]
final class TestIdentifierTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'blank')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\StringProvider::class, 'empty')]
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidTestIdentifier::class);

        TestIdentifier::fromString($value);
    }

    public function testFromStringReturnsTestIdentifier(): void
    {
        $value = self::faker()->word();

        $testIdentifier = TestIdentifier::fromString($value);

        self::assertSame($value, $testIdentifier->tostring());
    }
}
