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
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Markdown\InlineCode
 */
final class InlineCodeTest extends Framework\TestCase
{
    /**
     * @dataProvider provideValueAndCodeSpan
     */
    public function testToStringReturnsCodeSpan(
        string $value,
        string $codeSpan
    ): void {
        $inlineCode = Renderer\Markdown\InlineCode::fromString($value);

        self::assertSame($codeSpan, $inlineCode->toString());
    }

    /**
     * @return \Generator<string, array{0: string, 1: string}>
     */
    public static function provideValueAndCodeSpan(): iterable
    {
        $values = [
            'without-backticks' => [
                'Foo\BarTest::testBaz',
                '`Foo\BarTest::testBaz`',
            ],
            'with-single-backtick' => [
                'foo`bar',
                '``foo`bar``',
            ],
            'with-double-backticks' => [
                'foo``bar',
                '```foo``bar```',
            ],
            'starting-with-backtick' => [
                '`foo',
                '`` `foo ``',
            ],
            'ending-with-backtick' => [
                'foo`',
                '`` foo` ``',
            ],
            'starting-and-ending-with-space' => [
                ' foo ',
                '`  foo  `',
            ],
        ];

        foreach ($values as $key => [$value, $codeSpan]) {
            yield $key => [
                $value,
                $codeSpan,
            ];
        }
    }
}
