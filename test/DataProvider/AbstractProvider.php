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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider;

use Faker\Factory;
use Faker\Generator;

/**
 * @see https://github.com/ergebnis/data-provider/blob/3.2.0/src/AbstractProvider.php
 */
abstract class AbstractProvider
{
    final protected static function faker(string $locale = 'en_US'): Generator
    {
        /**
         * @var array<string, Generator> $fakers
         */
        static $fakers = [];

        if (!\array_key_exists($locale, $fakers)) {
            $faker = Factory::create($locale);

            $faker->seed(9001);

            $fakers[$locale] = $faker;
        }

        return $fakers[$locale];
    }

    /**
     * @param array<string, mixed> $values
     *
     * @throws Exception\EmptyValues
     *
     * @return \Generator<string, array{0: mixed}>
     */
    final protected static function provideDataForValues(array $values): \Generator
    {
        if ([] === $values) {
            throw Exception\EmptyValues::create();
        }

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    /**
     * @param array<string, mixed> $values
     *
     * @throws Exception\EmptyValues
     *
     * @return \Generator<string, array{0: mixed}>
     */
    final protected static function provideDataForValuesWhere(array $values, \Closure $test): \Generator
    {
        if ([] === $values) {
            throw Exception\EmptyValues::create();
        }

        $filtered = \array_filter($values, static function ($value) use ($test): bool {
            return true === $test($value);
        });

        if ([] === $filtered) {
            throw Exception\EmptyValues::filtered();
        }

        yield from self::provideDataForValues($filtered);
    }
}
