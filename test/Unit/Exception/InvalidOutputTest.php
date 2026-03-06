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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Exception;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidOutput
 */
final class InvalidOutputTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider provideOutput
     *
     * @param mixed $output
     */
    public function testNotResourceReturnsException($output)
    {
        $exception = Exception\InvalidOutput::notResource($output);

        $message = \sprintf(
            'Output needs to be a resource, got %s instead.',
            \is_object($output) ? \get_class($output) : \gettype($output)
        );

        self::assertSame($message, $exception->getMessage());
    }

    /**
     * @return \Generator<string, array{0: mixed}>
     */
    public static function provideOutput(): iterable
    {
        $faker = self::faker();

        $values = [
            'boolean' => $faker->boolean(),
            'integer' => $faker->numberBetween(),
            'string' => $faker->sentence(),
            'array' => $faker->words(),
            'null' => null,
            'object' => new \stdClass(),
        ];

        foreach ($values as $key => $output) {
            yield $key => [
                $output,
            ];
        }
    }
}
