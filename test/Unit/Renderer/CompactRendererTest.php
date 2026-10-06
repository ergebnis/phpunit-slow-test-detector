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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Renderer;

use Ergebnis\PHPUnit\SlowTestDetector\Renderer;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Renderer\CompactRenderer
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Sanitizer
 */
final class CompactRendererTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testBannerReturnsBanner(): void
    {
        $renderer = new Renderer\CompactRenderer(new Renderer\Sanitizer());

        $banner = $renderer->banner('ergebnis/phpunit-slow-test-detector');

        self::assertSame(\PHP_EOL . '=== ergebnis/phpunit-slow-test-detector ===' . \PHP_EOL, $banner);
    }

    public function testBannerEscapesControlCharactersAndLineBreaksInName(): void
    {
        $renderer = new Renderer\CompactRenderer(new Renderer\Sanitizer());

        $banner = $renderer->banner("foo\r\nbar\e");

        self::assertSame(\PHP_EOL . '=== foo\u{000D}\u{000A}bar\u{001B} ===' . \PHP_EOL, $banner);
    }

    public function testHeaderReturnsHeader(): void
    {
        $renderer = new Renderer\CompactRenderer(new Renderer\Sanitizer());

        $header = $renderer->header(
            'SLOW',
            'FooTest::test',
        );

        self::assertSame(\PHP_EOL . '--- SLOW: FooTest::test' . \PHP_EOL, $header);
    }

    public function testHeaderEscapesControlCharactersAndLineBreaksInTitle(): void
    {
        $renderer = new Renderer\CompactRenderer(new Renderer\Sanitizer());

        $header = $renderer->header(
            'SLOW',
            "FooTest::test with data set \"foo\r\nbar\e[31m\"",
        );

        self::assertSame(\PHP_EOL . '--- SLOW: FooTest::test with data set "foo\u{000D}\u{000A}bar\u{001B}[31m"' . \PHP_EOL, $header);
    }

    public function testBodyReturnsBody(): void
    {
        $renderer = new Renderer\CompactRenderer(new Renderer\Sanitizer());

        $body = $renderer->body('0.300 seconds (maximum 0.100 seconds)');

        self::assertSame('0.300 seconds (maximum 0.100 seconds)' . \PHP_EOL, $body);
    }

    public function testBodyEscapesControlCharactersButKeepsLineBreaks(): void
    {
        $renderer = new Renderer\CompactRenderer(new Renderer\Sanitizer());

        $body = $renderer->body("foo\nbar\e");

        self::assertSame("foo\nbar" . '\u{001B}' . \PHP_EOL, $body);
    }

    public function testSummaryReturnsSummaryWithOneCount(): void
    {
        $renderer = new Renderer\CompactRenderer(new Renderer\Sanitizer());

        $summary = $renderer->summary(
            'SLOW',
            [
                '1 test',
            ],
        );

        self::assertSame(\PHP_EOL . 'SLOW (1 test)' . \PHP_EOL, $summary);
    }

    public function testSummaryReturnsSummaryWithSeveralCounts(): void
    {
        $renderer = new Renderer\CompactRenderer(new Renderer\Sanitizer());

        $summary = $renderer->summary(
            'SLOW',
            [
                '11 tests',
                '1 not listed',
            ],
        );

        self::assertSame(\PHP_EOL . 'SLOW (11 tests, 1 not listed)' . \PHP_EOL, $summary);
    }
}
