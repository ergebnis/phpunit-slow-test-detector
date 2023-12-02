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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Version;

use Ergebnis\DataProvider;
use Ergebnis\PHPUnit\SlowTestDetector\Version;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Version\Patch
 */
final class PatchTest extends Framework\TestCase
{
    /**
     * @dataProvider  \Ergebnis\DataProvider\IntProvider::lessThanZero
     */
    public function testFromIntRejectsInvalidValue(int $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Value "%d" does not appear to be a valid value for a patch version.',
            $value,
        ));

        Version\Patch::fromInt($value);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testFromStringReturnsPatch(int $value): void
    {
        $patch = Version\Patch::fromInt($value);

        self::assertSame($value, $patch->toInt());
    }
}
