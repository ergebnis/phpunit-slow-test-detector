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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Renderer\GitHubActions;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Renderer;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Renderer\GitHubActions\Title
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidTitle
 */
final class TitleTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::empty
     */
    public function testFromStringThrowsInvalidTitleWhenValueIsBlankOrEmpty(string $value): void
    {
        $this->expectException(Exception\InvalidTitle::class);

        Renderer\GitHubActions\Title::fromString($value);
    }

    public function testFromStringReturnsTitleWhenValueIsNotBlankOrEmpty(): void
    {
        $value = self::faker()->sentence();

        $title = Renderer\GitHubActions\Title::fromString($value);

        self::assertSame($value, $title->toString());
    }
}
