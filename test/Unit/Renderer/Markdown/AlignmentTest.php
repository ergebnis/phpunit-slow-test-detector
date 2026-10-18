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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Renderer\Markdown;

use Ergebnis\PHPUnit\SlowTestDetector\Renderer;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\Alignment
 */
final class AlignmentTest extends Framework\TestCase
{
    public function testLeftReturnsAlignment(): void
    {
        $alignment = Renderer\Markdown\Alignment::left();

        self::assertSame(':--', $alignment->delimiter());
    }

    public function testRightReturnsAlignment(): void
    {
        $alignment = Renderer\Markdown\Alignment::right();

        self::assertSame('--:', $alignment->delimiter());
    }

    public function testCenterReturnsAlignment(): void
    {
        $alignment = Renderer\Markdown\Alignment::center();

        self::assertSame(':-:', $alignment->delimiter());
    }
}
