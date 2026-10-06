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
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Sanitizer
 */
final class SanitizerTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testSanitizeReturnsValueWhenValueDoesNotContainControlCharacters(): void
    {
        $value = self::faker()->sentence();

        $sanitizer = new Renderer\Sanitizer();

        self::assertSame($value, $sanitizer->sanitize($value));
    }

    /**
     * @dataProvider provideValueWithCharactersThatAreKept
     */
    public function testSanitizeKeepsCharacters(string $value): void
    {
        $sanitizer = new Renderer\Sanitizer();

        self::assertSame($value, $sanitizer->sanitize($value));
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function provideValueWithCharactersThatAreKept(): iterable
    {
        yield 'horizontal tab' => [
            "foo\tbar",
        ];

        yield 'line feed' => [
            "foo\nbar",
        ];

        yield 'carriage return followed by line feed' => [
            "foo\r\nbar",
        ];

        yield 'multi-byte character' => [
            'foo ä bar',
        ];
    }

    /**
     * @dataProvider provideValueWithControlCharactersAndSanitizedValue
     */
    public function testSanitizeReplacesControlCharactersWithEscapeSequences(
        string $value,
        string $sanitizedValue
    ): void {
        $sanitizer = new Renderer\Sanitizer();

        self::assertSame($sanitizedValue, $sanitizer->sanitize($value));
    }

    /**
     * @return \Generator<string, array{0: string, 1: string}>
     */
    public static function provideValueWithControlCharactersAndSanitizedValue(): iterable
    {
        yield 'null' => [
            "foo\x00bar",
            'foo\u{0000}bar',
        ];

        yield 'backspace' => [
            "foo\x08bar",
            'foo\u{0008}bar',
        ];

        yield 'vertical tab' => [
            "foo\x0Bbar",
            'foo\u{000B}bar',
        ];

        yield 'form feed' => [
            "foo\x0Cbar",
            'foo\u{000C}bar',
        ];

        yield 'carriage return not followed by line feed' => [
            "foo\rbar",
            'foo\u{000D}bar',
        ];

        yield 'escape' => [
            "\e[31mfoo\e[0m",
            '\u{001B}[31mfoo\u{001B}[0m',
        ];

        yield 'unit separator' => [
            "foo\x1Fbar",
            'foo\u{001F}bar',
        ];

        yield 'delete' => [
            "foo\x7Fbar",
            'foo\u{007F}bar',
        ];

        yield 'C1 control character U+0080' => [
            "foo\u{0080}bar",
            'foo\u{0080}bar',
        ];

        yield 'C1 control character U+009B' => [
            "foo\u{009B}bar",
            'foo\u{009B}bar',
        ];

        yield 'C1 control character U+009F' => [
            "foo\u{009F}bar",
            'foo\u{009F}bar',
        ];

        yield 'left-to-right embedding U+202A' => [
            "foo\u{202A}bar",
            'foo\u{202A}bar',
        ];

        yield 'right-to-left override U+202E' => [
            "foo\u{202E}bar",
            'foo\u{202E}bar',
        ];

        yield 'left-to-right isolate U+2066' => [
            "foo\u{2066}bar",
            'foo\u{2066}bar',
        ];

        yield 'pop directional isolate U+2069' => [
            "foo\u{2069}bar",
            'foo\u{2069}bar',
        ];
    }

    public function testSanitizeHandlesValueThatIsNotValidUtf8(): void
    {
        $sanitizer = new Renderer\Sanitizer();

        self::assertSame("\xFF\xFE" . 'foo\u{001B}', $sanitizer->sanitize("\xFF\xFEfoo\e"));
    }
}
