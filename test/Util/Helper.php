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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Util;

use Faker\Factory;
use Faker\Generator;

trait Helper
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
     * @return resource
     */
    final protected static function output()
    {
        $output = \fopen(
            'php://memory',
            'w+b',
        );

        self::assertIsResource($output);

        return $output;
    }

    /**
     * @param resource $output
     */
    final protected static function assertOutputIsIdenticalTo(
        string $expected,
        $output
    ): void {
        self::assertIsResource($output);

        \rewind($output);

        self::assertSame($expected, \stream_get_contents($output));
    }
}
