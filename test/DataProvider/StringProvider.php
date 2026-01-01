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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider;

/**
 * @see https://github.com/ergebnis/data-provider/blob/3.2.0/src/StringProvider.php
 */
final class StringProvider extends AbstractProvider
{
    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function arbitrary(): \Generator
    {
        yield from self::provideDataForValues(self::values());
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function blank(): \Generator
    {
        yield from self::provideDataForValuesWhere(self::values(), static function (string $value): bool {
            return '' === \trim($value)
                && '' !== $value;
        });
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function empty(): \Generator
    {
        yield from self::provideDataForValuesWhere(self::values(), static function (string $value): bool {
            return '' === $value;
        });
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function trimmed(): \Generator
    {
        yield from self::provideDataForValuesWhere(self::values(), static function (string $value): bool {
            return \trim($value) === $value
                && '' !== \trim($value);
        });
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function untrimmed(): \Generator
    {
        yield from self::provideDataForValuesWhere(self::values(), static function (string $value): bool {
            return \trim($value) !== $value
                && '' !== \trim($value);
        });
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function withWhitespace(): \Generator
    {
        yield from self::provideDataForValuesWhere(self::values(), static function (string $value): bool {
            return \trim($value) === $value
                && 1 === \preg_match('/\s/', $value);
        });
    }

    /**
     * @return array<string, string>
     */
    private static function values(): array
    {
        $faker = self::faker();

        $arbitraryValues = [
            'string-arbitrary-sentence' => $faker->sentence(),
            'string-arbitrary-word' => $faker->word(),
        ];

        $whitespaceCharacters = self::whitespaceCharacters();

        $blankValues = \array_combine(
            \array_map(static function (string $key): string {
                return \sprintf(
                    'string-blank-%s',
                    $key
                );
            }, \array_keys($whitespaceCharacters)),
            $whitespaceCharacters
        );

        $emptyValues = [
            'string-empty' => '',
        ];

        $untrimmedValues = \array_combine(
            \array_map(static function (string $key): string {
                return \sprintf(
                    'string-untrimmed-%s',
                    $key
                );
            }, \array_keys($whitespaceCharacters)),
            \array_map(static function (string $whitespaceCharacter) use ($faker): string {
                return \sprintf(
                    '%s%s%s',
                    \str_repeat(
                        $whitespaceCharacter,
                        $faker->numberBetween(1, 5)
                    ),
                    $faker->word(),
                    \str_repeat(
                        $whitespaceCharacter,
                        $faker->numberBetween(1, 5)
                    )
                );
            }, $whitespaceCharacters)
        );

        $withWhitespaceValues = \array_combine(
            \array_map(static function (string $key): string {
                return \sprintf(
                    'string-with-whitespace-%s',
                    $key
                );
            }, \array_keys($whitespaceCharacters)),
            \array_map(static function (string $whitespaceCharacter) use ($faker): string {
                /** @var list<string> $words */
                $words = $faker->words($faker->numberBetween(2, 5));

                return \implode(
                    $whitespaceCharacter,
                    $words
                );
            }, $whitespaceCharacters)
        );

        return \array_merge(
            $arbitraryValues,
            $blankValues,
            $emptyValues,
            $untrimmedValues,
            $withWhitespaceValues
        );
    }

    /**
     * @return array<string, string>
     */
    private static function whitespaceCharacters(): array
    {
        return [
            'carriage-return' => "\r",
            'line-feed' => "\n",
            'space' => ' ',
            'tab' => "\t",
        ];
    }
}
